<div>
    <div wire:ignore.self class="modal fade" id="codeSnippetModal"
         data-bs-backdrop="static" data-bs-keyboard="false"
         tabindex="-1" aria-hidden="true"
    >
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        Code Snippet
                        @if($deviceName)
                            &mdash; <span class="text-muted fw-normal">{{ $deviceName }}</span>
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    {{-- Description --}}
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-1">What is this?</h6>
                        <p class="mb-1">
                            This is a ready-to-use <strong>Arduino / ESP32 sketch</strong> that sends your sensor data
                            to this platform over Wi-Fi using an HTTP POST request.
                        </p>
                        <hr class="my-2">
                        <small>
                            <strong>How to use:</strong>
                            Copy the full sketch below, paste it into the Arduino IDE, fill in your Wi-Fi credentials
                            and bearer token, update the sensor variable values in <code>loop()</code>, then upload to
                            your device.
                        </small>
                    </div>

                    {{-- Section 1: Imports --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div>
                                <h6 class="mb-0">1. Required Libraries</h6>
                                <small class="text-muted">Add these at the very top of your sketch (before anything else).</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary"
                                    onclick="copyCode('snippet-imports')">
                                Copy
                            </button>
                        </div>
                        <pre id="snippet-imports" class="rounded mb-0" style="font-size: 0.85rem;"><code class="language-cpp">#include &lt;WiFi.h&gt;
#include &lt;HTTPClient.h&gt;</code></pre>
                    </div>

                    {{-- Section 2: Full Sketch --}}
                    <div class="mb-2">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div>
                                <h6 class="mb-0">2. Full Sketch</h6>
                                <small class="text-muted">Paste this into a new Arduino sketch. Replace the placeholders marked with <code>YOUR_</code>.</small>
                            </div>
                            <button class="btn btn-sm btn-outline-secondary"
                                    onclick="copyCode('snippet-full')">
                                Copy
                            </button>
                        </div>
                        <pre id="snippet-full" class="rounded mb-0" style="font-size: 0.85rem;"><code class="language-cpp">const char* ssid        = "YOUR_WIFI_SSID";
const char* password    = "YOUR_WIFI_PASSWORD";
const char* serverURL   = "{{ route('api.device.set.data') }}";
const char* bearerToken = "YOUR_BEARER_TOKEN";

// === SENSOR VARIABLES — update these with your actual readings ===
{{ $variablesString }}
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

    // Build JSON body — set your variable values above before sending
    {{ $jsonBodyString }}

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
}</code></pre>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById('codeSnippetModal').addEventListener('shown.bs.modal', function () {
            document.querySelectorAll('#codeSnippetModal pre code').forEach(function (el) {
                if (!el.dataset.highlighted) {
                    hljs.highlightElement(el);
                }
            });
        });

        function copyCode(preId) {
            const pre = document.getElementById(preId);
            const text = (pre.querySelector('code') ?? pre).textContent;
            navigator.clipboard.writeText(text).then(() => {
                const btn = pre.previousElementSibling.querySelector('button');
                const original = btn.textContent;
                btn.textContent = 'Copied!';
                btn.classList.replace('btn-outline-secondary', 'btn-success');
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.replace('btn-success', 'btn-outline-secondary');
                }, 2000);
            });
        }
    </script>
</div>
