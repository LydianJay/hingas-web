<x-site.basecomponent>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <!-- ID Card -->
        <div class="card shadow-lg rounded-4 p-4"
            style="min-width: 90%; max-width: 90%; max-height: 75%; min-height: 75%;">


                <div class="row">
                    <div class="col">
                        <div class="shadow p-1 border-1" style="width: 100%; height: 65vh;"> <!-- example height -->
                            <img src="{{ asset('assets/img/logo/logo-white.png') }}"
                                style="max-height: 100%; width: auto; display: block; margin: auto;" id="img">
                        </div>
                    </div>

                    <div class="col">
                        <h1 class="mt-5" style="font-size: 52px;">Name:</h1>
                        <h1 style="font-size: 48;" id="name">N/A</h1>


                        <h1 class="mt-5" style="font-size: 52px;" id="class">No Class</h1>
                        <span class="badge py-1 mt-5" id="badge">
                            <h1 style="font-size: 48;" class="mb-0" id="time">No data</h1>
                        </span>
                    </div>


                </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {


            @if (env('APP_DEBUG'))
                const ws = new WebSocket("ws://localhost:8765");
            @else
                const ws = new WebSocket("wss://websocket.hingaslifestyle.com");
            @endif


            function convertTo12Hour(time24) {
                let [hour, minute, second] = time24.split(':').map(Number);
                let period = hour >= 12 ? 'PM' : 'AM';
                let hour12 = hour % 12 || 12; 
                return `${hour12}:${minute.toString().padStart(2, '0')}:${second.toString().padStart(2, '0')} ${period}`;
            }

            function updateInfo(evnt) {
                console.log('Info');
                let route = "{{ route('get_latest_user') }}?rfid=" + event.data;
                fetch(route,
                {
                    method: 'GET',
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    document.getElementById('img').src = "{{asset('storage')}}/" + data.photo;
                    document.getElementById('name').innerText = data.fname + " " + data.lname;
                    document.getElementById('class').innerText = "Dance Class: " + data.name;
                    let bg = data.time_out == null ? 'bg-success' : 'bg-warning';
                    document.getElementById('badge').classList.add(bg);
                    let timeInfo = data.time_out == null ? convertTo12Hour(data.time_in) : convertTo12Hour(data.time_out);
                    document.getElementById('time').innerText = data.time_out == null ? "TIME IN " + timeInfo : "TIME OUT " + timeInfo ;
                })
                .catch(err => {
                    console.error('Error fetching ledger:', err);
                    document.getElementById('name').innerText = "ERROR Fetching User";
                    document.getElementById('class').innerText = "N/A";
                  
                    document.getElementById('badge').classList.add('bg-warning');
                    document.getElementById('time').innerText = "N/A";
                    
                });
            }
            ws.onopen = () => console.log('Connected!');
            ws.onmessage = (event) => updateInfo(event);
        });
    </script>
</x-site.basecomponent>