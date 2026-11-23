// Inclui a biblioteca para controlar o driver de motores TB6612FNG
#include <SparkFun_TB6612.h>

// Inclui a biblioteca para ler os sensores de linha QTR
#include <QTRSensors.h>

// Cria um objeto chamado "qtr" para usar os métodos da biblioteca QTRSensors
QTRSensors qtr;

// Define quantos sensores QTR você está usando
const uint8_t SensorCount = 8;

// Cria um array para armazenar os valores lidos de cada sensor QTR
uint16_t sensorValues[SensorCount];

// Define os pinos conectados ao driver de motores TB6612FNG
#define STBY 2    // Pino para ativar/desativar o driver (Standby)
#define PWMA 5    // Pino PWM para motor A (Esquerdo)
#define PWMB 6    // Pino PWM para motor B (Direito)
#define AIN1 8    // Pinos de controle do motor A
#define AIN2 9
#define BIN1 10   // Pinos de controle do motor B
#define BIN2 11

// Ajusta a direção dos motores se necessário (1 ou -1)
const int offsetA = 1;
const int offsetB = 1;

// Cria objetos para controlar cada motor usando a biblioteca TB6612
// Motor esquerdo (motorEsq) e direito (motorDir)
Motor motorEsq = Motor(AIN1, AIN2, PWMA, offsetA, STBY);
Motor motorDir = Motor(BIN1, BIN2, PWMB, offsetB, STBY);

// Variáveis usadas no controle PID
int PID = 0;                  // Valor final do controle PID
int somatorioErro = 0;        // Soma dos erros (usado para a parte integral do PID)
int deltaErro = 0;            // Diferença entre erro atual e anterior (derivativo)
int erroAnterior = 0;         // Armazena o erro anterior

const int velInercia = 30;    // Velocidade mínima para o motor começar a girar
int velMotor;                 // Velocidade calculada para aplicar no motor

const int velPista = 100;     // "Velocidade base" do robô na pista (pode ajustar entre 0 e 100)

// Constantes para o cálculo do PID
const int Kp = 50;            // Proporcional
const int Kd = 0;            // Derivativo
const float Ki = 0;        // Integral (float pois pode ser pequeno)

// Variáveis para controlar a parada do robô
int contFim = 3;              // Quantas vezes o sensor de parada deve ser ativado para parar de vez
bool flagFim = false;         // Auxiliar para detectar transição do sensor
const int sensorFim = 12;     // Pino do sensor de parada (ajuste para o seu caso)

void setup() 
{

  // Inicializa a comunicação serial para enviar informações para o computador
  Serial.begin(9600);

  // Configura os sensores QTR como tipo RC (recomenda usar RC para a maioria dos QTR)
  qtr.setTypeRC();

  // Define os pinos onde os sensores QTR estão conectados (ajuste para seus pinos)
  qtr.setSensorPins((const uint8_t[]){A0, A1, A2, A3, A4, A5, A6, A7}, SensorCount);

  // Configura o pino do LED embutido e do sensor de parada
  pinMode(LED_BUILTIN, OUTPUT); // LED do Arduino para indicar calibração
  pinMode(sensorFim, INPUT);    // Pino do sensor de parada como entrada

  // Liga o LED do Arduino para avisar que vai calibrar os sensores
  digitalWrite(LED_BUILTIN, HIGH);

  // Calibra os sensores QTR (move o robô sobre a linha durante esse tempo)
  // Faz 400 leituras para calibrar o mínimo/máximo de cada sensor
  for (uint16_t i = 0; i < 400; i++) qtr.calibrate();

  // Desliga o LED do Arduino, indicando que a calibração terminou
  digitalWrite(LED_BUILTIN, LOW);

  // Mostra na tela os valores mínimos capturados na calibração para cada sensor
  for (uint8_t i = 0; i < SensorCount; i++) 
  {
    Serial.print(qtr.calibrationOn.minimum[i]);
    Serial.print(' ');
  }
  Serial.println();

  // Mostra na tela os valores máximos capturados na calibração para cada sensor
  for (uint8_t i = 0; i < SensorCount; i++) 
  {
    Serial.print(qtr.calibrationOn.maximum[i]);
    Serial.print(' ');
  }
  Serial.println("\n");

  // Espera 2 segundos antes de começar o loop principal
  delay(2000);

}

// Função para calcular o PID, que ajusta a velocidade dos motores para seguir a linha
void calculaPID () 
{

  // Lê a posição da linha usando todos os sensores QTR
  // O valor vai de 0 (linha totalmente na esquerda) até 7000 (linha totalmente na direita)
  uint16_t position = qtr.readLineWhite(sensorValues);

  // Calcula o erro: diferença entre o centro ideal (3500) e a posição atual
  float erro = ((float)position - 3500) / 1000.0; // Normaliza para ficar entre -3.5 e +3.5

  // Soma dos erros para o termo integral do PID
  somatorioErro += erro;

  // Diferença entre erro atual e anterior para o termo derivativo
  deltaErro = erro - erroAnterior;

  // Atualiza o erro anterior para a próxima rodada
  erroAnterior = erro;

  // Calcula o valor do PID (Proporcional + Integral + Derivativo)
  PID = Kp * erro + Ki * somatorioErro + Kd * deltaErro;

}

// Função para ajustar os motores de acordo com o valor do PID (mantém o robô na linha)
void segueLinha () 
{

  // Calcula a velocidade para cada motor (direito e esquerdo) usando o PID
  int pwmDir = constrain(velPista + PID, -100, 100); // Motor direito
  int pwmEsq = constrain(velPista - PID, -100, 100); // Motor esquerdo

  // Calcula PWM para faixa completa do drive (positivo = frente, negativo = trás)
  if (pwmDir > 0) 
  {
    pwmDir = map(pwmDir, 0, 100, velInercia, 255);
  } 
  else if (pwmDir < 0) 
  {
    pwmDir = -map(-pwmDir, 0, 100, velInercia, 255); // negativo para trás
  }
  motorDir.drive(pwmDir); // motorDir usa drive()

  if (pwmEsq > 0) 
  {
    pwmEsq = map(pwmEsq, 0, 100, velInercia, 255);
  } 
  else if (pwmEsq < 0) 
  {
    pwmEsq = -map(-pwmEsq, 0, 100, velInercia, 255); // negativo para trás
  }
  motorEsq.drive(pwmEsq); // motorEsq usa drive()
}

// Função para parar ambos os motores
void paraMotores() 
{
  motorDir.brake(); // Para o motor direito
  motorEsq.brake(); // Para o motor esquerdo
}

// Função principal que fica repetindo enquanto o Arduino está ligado
void loop() 
{
   
  // Se o contador de final de pista for zero ou menor, para os motores
  if (contFim <= 0) 
  {
    paraMotores();
    Serial.println("Parou");
  } 
  else 
  {
      // Calcula o PID usando os sensores
      calculaPID();

      // Ajusta os motores de acordo com o valor do PID
      segueLinha();

      /*/
      // Lê o sensor de parada (detecta uma linha branca de fim de pista)
      int leituraSensorFim = digitalRead(sensorFim);
      Serial.print("Sensor parada: ");
      Serial.println(leituraSensorFim);

      // Se o sensor de parada detectar branco (0), ativa a flag
      if (leituraSensorFim == 0) 
      {
         flagFim = true;
      }

      // Se o sensor mudar para preto (1) E a flag estava ativa, decrementa contador de parada
      if (leituraSensorFim == 1 && flagFim) 
      {
         contFim--;
         flagFim = false;
         // Se chegou no fim, espera 1 segundo antes de parar completamente
         if (contFim == 0) 
         {
            delay(1000);
         }
      }
      */
  }
}