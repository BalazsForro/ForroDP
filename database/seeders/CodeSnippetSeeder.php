<?php

namespace Database\Seeders;

use App\Models\CodeSnippet;
use Illuminate\Database\Seeder;

class CodeSnippetSeeder extends Seeder
{
    public function run(): void
    {
        // Arduino (device_type_id = 1)
        CodeSnippet::updateOrCreate(
            ['device_type_id' => 1],
            ['content' => <<<'CODE'
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid        = "YOUR_WIFI_SSID";
const char* password    = "YOUR_WIFI_PASSWORD";
const char* serverURL   = "{{SERVER_URL}}";
const char* bearerToken = "YOUR_BEARER_TOKEN";

// === SENSOR VARIABLES — update these with your actual readings ===
{{VARIABLES}}
// ================================================================

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected!");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    http.begin(serverURL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Authorization", String("Bearer ") + bearerToken);

    {{JSON_BODY}}

    Serial.println("Sending: " + jsonBody);

    int httpResponseCode = http.POST(jsonBody);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("Response code: " + String(httpResponseCode));
      Serial.println("Response: " + response);
    } else {
      Serial.println("Error: " + String(httpResponseCode));
    }

    http.end();
  }

  delay(10000); // Send data every 10 seconds
}
CODE]
        );

        // ESP32 (device_type_id = 2)
        CodeSnippet::updateOrCreate(
            ['device_type_id' => 2],
            ['content' => <<<'CODE'
#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid        = "YOUR_WIFI_SSID";
const char* password    = "YOUR_WIFI_PASSWORD";
const char* serverURL   = "{{SERVER_URL}}";
const char* bearerToken = "YOUR_BEARER_TOKEN";

// === SENSOR VARIABLES — update these with your actual readings ===
{{VARIABLES}}
// ================================================================

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("\nWiFi connected!");
  Serial.println("IP: " + WiFi.localIP().toString());
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    http.begin(serverURL);
    http.addHeader("Content-Type", "application/json");
    http.addHeader("Authorization", String("Bearer ") + bearerToken);

    {{JSON_BODY}}

    Serial.println("Sending: " + jsonBody);

    int httpResponseCode = http.POST(jsonBody);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("HTTP " + String(httpResponseCode) + ": " + response);
    } else {
      Serial.println("POST failed: " + String(http.errorToString(httpResponseCode)));
    }

    http.end();
  } else {
    Serial.println("WiFi disconnected, reconnecting...");
    WiFi.reconnect();
  }

  delay(10000); // Send data every 10 seconds
}
CODE]
        );

        // Raspberry Pi (device_type_id = 3)
        CodeSnippet::updateOrCreate(
            ['device_type_id' => 3],
            ['content' => <<<'CODE'
import requests
import time

SERVER_URL   = "{{SERVER_URL}}"
BEARER_TOKEN = "YOUR_BEARER_TOKEN"

def read_sensors():
    # Replace these with your actual sensor readings
{{VARIABLES_PYTHON}}
    return payload

def send_data(payload):
    headers = {
        "Content-Type": "application/json",
        "Authorization": f"Bearer {BEARER_TOKEN}",
    }
    try:
        response = requests.post(SERVER_URL, json=payload, headers=headers, timeout=10)
        print(f"Response {response.status_code}: {response.text}")
    except requests.exceptions.RequestException as e:
        print(f"Error sending data: {e}")

if __name__ == "__main__":
    while True:
        payload = read_sensors()
        print(f"Sending: {payload}")
        send_data(payload)
        time.sleep(10)  # Send data every 10 seconds
CODE]
        );

        // Other (device_type_id = 4)
        CodeSnippet::updateOrCreate(
            ['device_type_id' => 4],
            ['content' => <<<'CODE'
# Send sensor data using curl
# Replace YOUR_BEARER_TOKEN with your actual token

curl -X POST "{{SERVER_URL}}" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer YOUR_BEARER_TOKEN" \
  -d '{{JSON_BODY_STATIC}}'

# Example response:
# {"message": "Data received successfully"}
CODE]
        );
    }
}