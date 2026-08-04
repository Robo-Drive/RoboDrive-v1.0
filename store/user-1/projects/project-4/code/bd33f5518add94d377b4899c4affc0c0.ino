/**
 * @file teste_motores.ino
 * @brief Código de teste simples para acionamento dos motores do robô com ESP32.
 * @desc Este código gira cada motor para frente e para trás para testar a pinagem e o driver TB6612FNG / L298N.
 */

#include <Arduino.h>

// Controle dos Motores (Pinos idênticos ao código principal)
#define PIN_AIN1 12
#define PIN_AIN2 13
#define PIN_PWMA 14 // Motor Esquerdo

#define PIN_BIN1 25
#define PIN_BIN2 26
#define PIN_PWMB 27 // Motor Direito

#define PIN_STBY 23 // Pino Standby

// LEDC canais do ESP32
#define PWM_FREQ 5000
#define PWM_RES 8
#define LEDC_CH_A 0
#define LEDC_CH_B 1

void setup() {
  Serial.begin(115200);
  Serial.println("--- Teste de Motores Iniciado ---");

  // Configurar pinos como saídas
  pinMode(PIN_AIN1, OUTPUT);
  pinMode(PIN_AIN2, OUTPUT);
  pinMode(PIN_BIN1, OUTPUT);
  pinMode(PIN_BIN2, OUTPUT);
  pinMode(PIN_STBY, OUTPUT);

  // Ativar o driver
  digitalWrite(PIN_STBY, HIGH);

  // Inicializar o PWM do ESP32
  ledcSetup(LEDC_CH_A, PWM_FREQ, PWM_RES);
  ledcSetup(LEDC_CH_B, PWM_FREQ, PWM_RES);
  ledcAttachPin(PIN_PWMA, LEDC_CH_A);
  ledcAttachPin(PIN_PWMB, LEDC_CH_B);
}

void loop() {
  // 1. Testar Motor Esquerdo (A) - Frente
  Serial.println("Motor Esquerdo: FRENTE (velocidade: 150)");
  digitalWrite(PIN_AIN1, HIGH);
  digitalWrite(PIN_AIN2, LOW);
  ledcWrite(LEDC_CH_A, 150);
  delay(2000);

  // Parar Motor Esquerdo
  Serial.println("Motor Esquerdo: PARADO");
  ledcWrite(LEDC_CH_A, 0);
  delay(1000);

  // 2. Testar Motor Esquerdo (A) - Trás
  Serial.println("Motor Esquerdo: TRAS (velocidade: 150)");
  digitalWrite(PIN_AIN1, LOW);
  digitalWrite(PIN_AIN2, HIGH);
  ledcWrite(LEDC_CH_A, 150);
  delay(2000);

  // Parar Motor Esquerdo
  ledcWrite(LEDC_CH_A, 0);
  delay(1000);

  // 3. Testar Motor Direito (B) - Frente
  Serial.println("Motor Direito: FRENTE (velocidade: 150)");
  digitalWrite(PIN_BIN1, HIGH);
  digitalWrite(PIN_BIN2, LOW);
  ledcWrite(LEDC_CH_B, 150);
  delay(2000);

  // Parar Motor Direito
  Serial.println("Motor Direito: PARADO");
  ledcWrite(LEDC_CH_B, 0);
  delay(1000);

  // 4. Testar Motor Direito (B) - Trás
  Serial.println("Motor Direito: TRAS (velocidade: 150)");
  digitalWrite(PIN_BIN1, LOW);
  digitalWrite(PIN_BIN2, HIGH);
  ledcWrite(LEDC_CH_B, 150);
  delay(2000);

  // Parar Motor Direito
  ledcWrite(LEDC_CH_B, 0);
  delay(2000);
}
