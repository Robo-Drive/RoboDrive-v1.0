/**
 * @file teste_sensores.ino
 * @brief Código de teste simples para os 5 sensores reflexivos infravermelhos com ESP32.
 * @desc Lê os valores analógicos das portas especificadas e os exibe de forma legível no Monitor Serial.
 */

#include <Arduino.h>

#define NUM_SENSORS 5
const int sensorPins[NUM_SENSORS] = {36, 39, 34, 35, 32}; // GPIOs do ESP32 (ADC1)

void setup() {
  Serial.begin(115200);
  Serial.println("--- Teste de Sensores IR Iniciado ---");
  Serial.println("Coloque os sensores sobre a linha branca e depois sobre a linha preta para ver a variação.");
  delay(1000);
}

void loop() {
  // Lê os valores de cada sensor (O ESP32 possui resolução ADC padrão de 12-bits: 0 a 4095)
  int s1 = analogRead(sensorPins[0]);
  int s2 = analogRead(sensorPins[1]);
  int s3 = analogRead(sensorPins[2]);
  int s4 = analogRead(sensorPins[3]);
  int s5 = analogRead(sensorPins[4]);

  // Exibe os valores no formato adequado para o Serial Plotter do Arduino
  // Ex: S1:1234, S2:2345, S3:3456, S4:1123, S5:4000
  Serial.print("S1:"); Serial.print(s1); Serial.print("\t");
  Serial.print("S2:"); Serial.print(s2); Serial.print("\t");
  Serial.print("S3:"); Serial.print(s3); Serial.print("\t");
  Serial.print("S4:"); Serial.print(s4); Serial.print("\t");
  Serial.print("S5:"); Serial.print(s5);
  Serial.println();

  // Pequena pausa para não poluir o terminal muito rápido
  delay(150);
}
