<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Dark Mode -->
    <script>
        let switchColorScheme = function (theme) {
            if (undefined !== theme) {
                switch (theme.toString().toLowerCase()) {
                    case 'light':
                        localStorage.edenTheme = 'light';
                        break;
                    case 'dark':
                        localStorage.edenTheme = 'dark';
                        break;
                    case 'system':
                        localStorage.removeItem('edenTheme');
                        break;
                    default:
                    // Nothing to do
                }
            }

            // On page load or when changing themes, best to add inline in `head` to avoid FOUC
            if (localStorage.edenTheme === 'dark' || (!('edenTheme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        };
        // Check Initial Color Scheme
        (switchColorScheme)()
    </script>

    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700&display=swap">

    @livewireStyles
    @stack('css')

    @vite('resources/css/app.css')
</head>
<body class="font-sans antialiased" x-data x-eden-nice-scroll>

<div class="min-h-screen bg-slate-100 text-slate-500 flex w-full dark:bg-slate-800 dark:text-slate-400">
    <main class="grow flex flex-col">
        <div data-dusk="container" class="px-4 py-4 grow">

            <div class="h-full w-full flex flex-col justify-center items-center">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="w-96" viewBox="0 0 879.009 736.76">
                        <defs>
                            <linearGradient id="linear-gradient" x1="0.5" y1="-0.227" x2="0.5" y2="0.559" gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#fff"/>
                                <stop offset="0.476" stop-color="#fff"/>
                                <stop offset="1" stop-color="#fff" stop-opacity="0"/>
                            </linearGradient>
                            <linearGradient id="linear-gradient-2" x1="86.402" y1="0.412" x2="87.007" y2="0.823" gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#4e9cff"/>
                                <stop offset="0.984" stop-color="#176ad4"/>
                            </linearGradient>
                            <linearGradient id="linear-gradient-3" x1="1.154" y1="0.568" x2="0.42" y2="0.49" gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#f2bfad"/>
                                <stop offset="1" stop-color="#f2dad4"/>
                            </linearGradient>
                            <linearGradient id="linear-gradient-4" x1="0.202" y1="0.241" x2="1.023" y2="0.87" xlink:href="#linear-gradient-2"/>
                            <linearGradient id="linear-gradient-5" x1="0.51" y1="0.622" x2="0.569" y2="1.822" gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#f2bfad"/>
                                <stop offset="0.704" stop-color="#f2dad4"/>
                            </linearGradient>
                            <linearGradient id="linear-gradient-6" x1="0.404" y1="0.306" x2="0.835" y2="1.076" xlink:href="#linear-gradient-3"/>
                            <linearGradient id="linear-gradient-7" x1="0.386" y1="0.075" x2="0.681" y2="1.17" gradientUnits="objectBoundingBox">
                                <stop offset="0" stop-color="#f6dbdb"/>
                                <stop offset="1" stop-color="#eb9a99"/>
                            </linearGradient>
                            <linearGradient id="linear-gradient-8" x1="0.314" y1="0.412" x2="0.919" y2="0.823" xlink:href="#linear-gradient-2"/>
                        </defs>
                        <g id="Group_3" data-name="Group 3" transform="translate(-1070.487 -556.911)">
                            <g id="Group_1" data-name="Group 1">
                                <path id="Path_1" data-name="Path 1" d="M1793.943,912.765a6.107,6.107,0,0,0-12.068,1.887c.877,5.688,1.992,23.51-4.157,30.68a8.209,8.209,0,0,1-4.958,2.851l.56-33.941a8.828,8.828,0,1,0-17.652.073l1.595,64.412a8.239,8.239,0,0,1-5.166-2.878c-6.15-7.171-5.034-24.992-4.158-30.68a6.107,6.107,0,1,0-12.067-1.888c-.439,2.8-3.969,27.756,6.937,40.5a20.677,20.677,0,0,0,14.759,7.255l.694,28.036h13.328l.967-58.576a20.637,20.637,0,0,0,14.448-7.231C1797.912,940.521,1794.382,915.568,1793.943,912.765Z" fill="#bdbdbd"/>
                                <path id="Path_2" data-name="Path 2" d="M1224.894,967.368a3.893,3.893,0,1,0-7.692,1.2c.558,3.626,1.269,14.986-2.651,19.557a5.234,5.234,0,0,1-3.16,1.818l.357-21.637a5.628,5.628,0,1,0-11.253.047l1.017,41.06a5.246,5.246,0,0,1-3.293-1.834c-3.92-4.571-3.209-15.932-2.651-19.558a3.893,3.893,0,1,0-7.692-1.2c-.28,1.787-2.531,17.694,4.422,25.818a13.18,13.18,0,0,0,9.408,4.624l.443,17.873h8.5l.616-37.34a13.162,13.162,0,0,0,9.211-4.61C1227.425,985.061,1225.174,969.155,1224.894,967.368Z" fill="#bdbdbd"/>
                                <ellipse id="Ellipse_1" data-name="Ellipse 1" cx="439.504" cy="155.593" rx="439.504" ry="155.593" transform="translate(1070.487 982.485)" fill="url(#linear-gradient)"/>
                            </g>
                            <g id="Group_2" data-name="Group 2">
                                <path id="Path_3" data-name="Path 3" d="M1580.148,949.483s-.255,14.973-.007,16.473-5.861,10.275-8.361,9.886-7.5-1.222-8.22-2.055-.4-2.8-.146-4.592,2.127-18.1,2.127-18.934S1577.371,947.206,1580.148,949.483Z" fill="#ffd3bc"/>
                                <path id="Path_4" data-name="Path 4" d="M1570.455,965.095c-3.127,1.69-4.882,6.831-5.326,6.894s-1.271-.063-1.271-.571.127-2.223-.444-2.223-1.207,2.794-1.334,6.859-1.573,13.758,0,14.991c.986.773,10.036,5.526,10.29,6.161s-.191,5.082.572,5.78,2.54,2.414,3.937,2.478,9.465.889,13.276,0,8.638-2.732,8.765-3.24-.953-11.306-2.159-12.64-9.909-13.275-10.608-14.355a12.325,12.325,0,0,1-1.207-5.209c0-1.524-1.379-4.85-3.945-5.651C1578.327,963.534,1572.805,963.825,1570.455,965.095Z" fill="url(#linear-gradient-2)"/>
                                <path id="Path_5" data-name="Path 5" d="M1488.885,958.141s0,8.72-.252,10.22,5.862,10.275,8.361,9.886,7.5-1.221,8.22-2.054.405-2.8.147-4.593-1.869-11.848-1.869-12.681S1491.662,955.864,1488.885,958.141Z" fill="#ffd3bc"/>
                                <path id="Path_6" data-name="Path 6" d="M1496.934,765.727c-3.538,12.864-10.229,56.935-10.229,78.351s-2.142,76.025-2.142,84.324-.893,28.888,1.251,29.96,7.149,4.289,13.94,3.217,8.579-5.362,8.757-9.294,1.788-32.169,3.4-43.071,7.328-31.928,11.081-45.453,12.51-40.72,14.834-40.616,16.8,59.684,18,63.061,3.5,51.353,3.653,55.378,0,7.9,2.535,9.543,10.288,5.665,14.313,5.964,6.709-6.113,7.455-8.052,2.067-66.108,1.591-83.711.915-50.429.1-61.133-3.431-33.643-5.1-38.468S1496.934,765.727,1496.934,765.727Z" fill="#192b59"/>
                                <path id="Path_7" data-name="Path 7" d="M1471.949,616.2c3.376-3.752,19.134-21.511,22.261-22.136s10.737-2,12.434-3.752,8.875-24.285,6.034-28.384-13.806-2.935-17.128,4.041-3.156,10.632-8.638,15.449-29.367,24.346-31.591,26.319,2.188,11.557,5.676,13.218S1471.949,616.2,1471.949,616.2Z" fill="url(#linear-gradient-3)"/>
                                <path id="Path_8" data-name="Path 8" d="M1515.495,621.611c-8.051,1.688-21.427,3.117-25.842,4.545s-21.816,7.532-21.687,4.935,8.052-14.025,6.623-15.194-10-2.6-11.427-4.415-3.377-6.1-5.325-5.584-24.531,21.555-22.985,29.608c3.645,18.986,57.018,32.82,58.236,41.1s-4.14,87.678-1.218,90.6,33.854,14.126,49.441,15.1,42.93-4.456,44.423-8.561-7.606-82.991-6.55-82.991,1.354,24.833,13.226,31.255c7.837,4.239,18.619-.128,20.682-2.467s-7.367-35.354-11.635-46.487-8.349-34.14-18-42.3-23.268-6.775-27.957-8.694-20.421-.544-24.388-.958S1515.495,621.611,1515.495,621.611Z" fill="url(#linear-gradient-4)"/>
                                <path id="Path_9" data-name="Path 9" d="M1616.057,704.62s-13.88,4.2-14.963,5.639-3.008,7.219,0,9.626,6.306,4.206,9.875,1.43,6.822-6.515,8.884-6.986,8.23.415,10.82-.6,7.5-20.65,5.52-21.84S1616.057,704.62,1616.057,704.62Z" fill="url(#linear-gradient-5)"/>
                                <path id="Path_10" data-name="Path 10" d="M1515.557,618.957c-4.48-.929-13.127-9.386-13.921-17.98-.773-8.373-6.929-31.9,14.5-40.729,20.109-8.281,40.8.238,43.779,10.451,2.778,9.537-20.828,9.966-29.483,16.177s-9.243,10.335-11.4,9.353S1515.557,618.957,1515.557,618.957Z" fill="#e64d4e"/>
                                <path id="Path_11" data-name="Path 11" d="M1515.195,602.194c-.285,3.835-1.249,22.384.357,25.115,1.475,2.508,4.095,9.8,14.374,9.475s16.944-10.6,17.506-11.8-.643-16.061-.643-18.55-13.812-8.352-19.353-8.191S1515.462,598.587,1515.195,602.194Z" fill="url(#linear-gradient-6)"/>
                                <path id="Path_12" data-name="Path 12" d="M1516.14,602.249c1.16-.033-.21,7.036,1.89,9.346s11.24,14.358,20.912,13.18c8.364-1.018,7.164-8.225,8.37-14.159,1.094-5.388,4.571-5.851,5.792-19.918.472-5.445,3.045-13.967-1.681-15.542s-22.472-5.776-26.882,0-4.831,19.637-7.141,17.852-7.141-10.5-9.976-5.776S1509.943,602.427,1516.14,602.249Z" fill="#f2dad4"/>
                                <path id="Path_13" data-name="Path 13" d="M1590.8,674.188l-28.148,35.588a2.8,2.8,0,0,0,2.241,4.53l52.028-.884a2.794,2.794,0,0,0,2.191-1.12l26.591-35.535a2.539,2.539,0,0,0-2.053-4.06l-50.68.42A2.8,2.8,0,0,0,1590.8,674.188Z" fill="url(#linear-gradient-7)"/>
                                <path id="Path_14" data-name="Path 14" d="M1635.309,688.522c-2.739.276-6.717,1.816-6.717,3.248s3.193,1.825,4.074,1.959,2.038.409,1.707.849-.991,2.644-2.753,2.258-9.471-.5-10.958-.5-3.248,1.047-3.358,2.148-.441,1.322-1.322,1.377-2.478.881-2.478,2.312,3.537,2.721,3.537,3.227,6.939,4.744,8.3,5.532,3.9,2.791,5.333,2.791,3.829-1.36,4.008-3.328,3.006-3.4,3.042-4.366-.9-1.682-.788-2.29,2.577-1.468,2.577-3.006-.966-3.615-1.109-4.259-.716-2.04-.143-2.219,2.576-2.04,2.218-3.758S1636.575,688.394,1635.309,688.522Z" fill="#ffd3bc"/>
                                <path id="Path_15" data-name="Path 15" d="M1498.32,967.5c3.126,1.69,4.881,6.83,5.326,6.894s1.27-.064,1.27-.572-.127-2.223.445-2.223,1.207,2.8,1.334,6.86,1.572,13.758,0,14.99c-.986.773-10.036,5.527-10.29,6.162s.19,5.081-.572,5.78-2.541,2.413-3.938,2.477-9.464.889-13.275,0-8.639-2.731-8.766-3.239.953-11.307,2.16-12.64,9.909-13.276,10.607-14.356a12.324,12.324,0,0,0,1.207-5.208c0-1.525,1.38-4.851,3.946-5.652C1490.447,965.94,1495.97,966.23,1498.32,967.5Z" fill="url(#linear-gradient-8)"/>
                            </g>
                        </g>
                    </svg>
                </div>

                <h1 class="text-2xl mb-3">unauthorized access</h1>
                <p class="text-lg text-slate-600">what you were trying to view are not authorized to</p>
            </div>

        </div>
    </main>
</div>

@stack('js')

@vite('resources/js/app.js')
@livewireScripts
</body>
</html>
