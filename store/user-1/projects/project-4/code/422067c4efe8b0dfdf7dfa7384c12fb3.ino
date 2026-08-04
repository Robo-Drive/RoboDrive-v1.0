/**
 * @file seguidor_linha_pid.ino
 * @brief Código de controle PID para Robô Seguidor de Linha usando ESP32 e Driver TB6612FNG.
 * @author Antigravity
 * @date 2026
 */

#include <Arduino.h>

// --- DEFINIÇÕES DOS PINOS ---

// Sensores IR (Entradas Analógicas)
#define NUM_SENSORS 5
const int sensorPins[NUM_SENSORS] = {36, 39, 34, 35, 32}; // GPIOs do ESP32 (ADC1)

// Controle dos Motores (TB6612FNG / L298N)
#define PIN_AIN1 12
#define PIN_AIN2 13
#define PIN_PWMA 14 // PWM Motor Esquerdo

#define PIN_BIN1 25
#define PIN_BIN2 26
#define PIN_PWMB 27 // PWM Motor Direito

#define PIN_STBY 23 // Pino Standby do TB6612FNG (manter HIGH)

// Canais PWM para o ESP32 (LEDC)
#define PWM_FREQ 5000
#define PWM_RES 8
#define LEDC_CH_A 0
#define LEDC_CH_B 1

// --- CONSTANTES DO PID ---
float Kp = 0.08;  // Ganho Proporcional (ajuste inicial)
float Ki = 0.000; // Ganho Integral (geralmente muito baixo ou zero)
float Kd = 0.45;  // Ganho Derivativo (evita oscilação)

// --- CONFIGURAÇÕES DE VELOCIDADE ---
const int VELOCIDADE_BASE = 180; // Velocidade base dos motores (0 a 255)
const int VELOCIDADE_MAX  = 255; // Velocidade máxima
const int VELOCIDADE_MIN  = 0;   // Velocidade mínima (ou reversão/freio se negativo)

// --- VARIÁVEIS DE CONTROLE ---
int sensorValues[NUM_SENSORS];
int sensorMin[NUM_SENSORS] = {4095, 4095, 4095, 4095, 4095};
int sensorMax[NUM_SENSORS] = {0, 0, 0, 0, 0};

float error = 0;
float lastError = 0;
float integral = 0;

// --- PROTÓTIPOS DE FUNÇÕES ---
void calibrarSensores();
float obterPosicaoLinha();
void calcularPID();
void controlarMotores(int velocidadeEsquerda, int velocidadeDireita);

void setup() {
  Serial.begin(115200);

  // Configuração dos pinos do motor
  pinMode(PIN_AIN1, OUTPUT);
  pinMode(PIN_AIN2, OUTPUT);
  pinMode(PIN_BIN1, OUTPUT);
  pinMode(PIN_BIN2, OUTPUT);
  pinMode(PIN_STBY, OUTPUT);
  
  digitalWrite(PIN_STBY, HIGH); // Ativa o driver de motor

  // Configuração do PWM usando LEDC (padrão ESP32)
  ledcSetup(LEDC_CH_A, PWM_FREQ, PWM_RES);
  ledcSetup(LEDC_CH_B, PWM_FREQ, PWM_RES);
  ledcAttachPin(PIN_PWMA, LEDC_CH_A);
  ledcAttachPin(PIN_PWMB, LEDC_CH_B);

  // Inicializa motores parados
  controlarMotores(0, 0);

  // Modo de calibração automática na inicialização
  calibrarSensores();
}

void loop() {
  float posicao = obterPosicaoLinha();
  
  // Se estiver centralizado no meio, o erro é 0. 
  // O valor da posição varia de 0 (sensor mais à esquerda) a 4000 (sensor mais à direita).
  // A posição central para 5 sensores é 2000.
  error = posicao - 2000;

  calcularPID();

  delay(1); // Pequeno delay para estabilidade do loop
}

/**
 * @brief Realiza a calibração automática dos sensores IR.
 * Recomenda-se mover o robô lateralmente sobre a linha preta/branca durante a calibração.
 */
void calibrarSensores() {
  Serial.println("Iniciando calibração em 2 segundos... Mova o robô sobre a pista.");
  delay(2000);
  
  unsigned long startTime = millis();
  // Calibra por 5 segundos
  while (millis() - startTime < 5000) {
    for (int i = 0; i < NUM_SENSORS; i++) {
      int val = analogRead(sensorPins[i]);
      if (val < sensorMin[i]) sensorMin[i] = val;
      if (val > sensorMax[i]) sensorMax[i] = val;
    }
    delay(20);
  }
  
  Serial.println("Calibração concluída!");
  for (int i = 0; i < NUM_SENSORS; i++) {
    Serial.printf("Sensor %d - Min: %d | Max: %d\n", i, sensorMin[i], sensorMax[i]);
  }
  delay(1000);
}

/**
 * @brief Lê os sensores, normaliza os valores calibrados e calcula a posição da linha.
 * @return Posição estimada da linha de 0 (esquerda) a 4000 (direita).
 */
float obterPosicaoLinha() {
  unsigned long somaPonderada = 0;
  unsigned long somaValores = 0;
  bool linhaDetectada = false;

  for (int i = 0; i < NUM_SENSORS; i++) {
    int valRaw = analogRead(sensorPins[i]);
    
    // Normalização (Mapeia o sinal de 0 a 1000 baseado na calibração)
    int valCalibrado = map(valRaw, sensorMin[i], sensorMax[i], 0, 1000);
    valCalibrado = constrain(valCalibrado, 0, 1000);

    // Se estivermos seguindo uma linha PRETA em fundo BRANCO,
    // o sensor sobre a linha preta lerá um valor alto (pouca reflexão).
    // Caso queira seguir linha BRANCA em fundo PRETO, inverta o valor:
    // valCalibrado = 1000 - valCalibrado;

    sensorValues[i] = valCalibrado;

    // Considera linha detectada se o valor for significativamente alto
    if (valCalibrado > 200) {
      linhaDetectada = true;
    }

    somaPonderada += (unsigned long)valCalibrado * (i * 1000);
    somaValores += valCalibrado;
  }

  // Se a linha sumir, mantém o último erro (evita que o robô se perca)
  if (!linhaDetectada) {
    if (lastError < 0) return 0; // Linha perdida à esquerda
    else return 4000;            // Linha perdida à direita
  }

  return (float)somaPonderada / somaValores;
}

/**
 * @brief Algoritmo PID para ajustar a velocidade dos motores.
 */
void calcularPID() {
  float proporcional = error;
  integral += error;
  // Limita a ação integral para evitar o efeito "windup"
  integral = constrain(integral, -10000, 10000);
  float derivativo = error - lastError;

  float correction = (Kp * proporcional) + (Ki * integral) + (Kd * derivativo);
  lastError = error;

  int velMotorEsquerdo = VELOCIDADE_BASE + correction;
  int velMotorDireito = VELOCIDADE_BASE - correction;

  // Garante que as velocidades fiquem dentro dos limites operacionais
  velMotorEsquerdo = constrain(velMotorEsquerdo, VELOCIDADE_MIN, VELOCIDADE_MAX);
  velMotorDireito = constrain(velMotorDireito, VELOCIDADE_MIN, VELOCIDADE_MAX);

  controlarMotores(velMotorEsquerdo, velMotorDireito);
}

/**
 * @brief Envia os sinais de controle de velocidade e direção para os motores.
 */
void controlarMotores(int velocidadeEsquerda, int velocidadeDireita) {
  // Motor Esquerdo (Motor A)
  if (velocidadeEsquerda >= 0) {
    digitalWrite(PIN_AIN1, HIGH);
    digitalWrite(PIN_AIN2, LOW);
    ledcWrite(LEDC_CH_A, velocidadeEsquerda);
  } else {
    // Reversão/Freio se necessário
    digitalWrite(PIN_AIN1, LOW);
    digitalWrite(PIN_AIN2, HIGH);
    ledcWrite(LEDC_CH_A, abs(velocidadeEsquerda));
  }

  // Motor Direito (Motor B)
  if (velocidadeDireita >= 0) {
    digitalWrite(PIN_BIN1, HIGH);
    digitalWrite(PIN_BIN2, LOW);
    ledcWrite(LEDC_CH_B, velocidadeDireita);
  } else {
    // Reversão/Freio
    digitalWrite(PIN_BIN1, LOW);
    digitalWrite(PIN_BIN2, HIGH);
    ledcWrite(LEDC_CH_B, abs(velocidadeDireita));
  }
}
