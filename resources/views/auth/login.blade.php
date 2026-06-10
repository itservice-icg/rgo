<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ทะเบียนออนไลน์ (RGO)</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo2.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
        }

        /* Custom styles for SVG paths to achieve the desired curves */
        .svg-top-left {
            transform: rotate(20deg) scale(1.5);
            /* Rotate and scale to spread the curve */
            transform-origin: top left;
        }

        .svg-bottom-right {
            transform: rotate(-20deg) scale(1.5);
            /* Rotate and scale */
            transform-origin: bottom right;
        }

        /* New styles for the four circles in the top-right corner */
        .top-right-circles {
            position: absolute;
            top: 0;
            right: 0;
            z-index: 10;
            padding-right: 40px;
            /* Adjust as needed */
            display: flex;
            /* To contain circles */
            flex-wrap: wrap;
            /* In case of specific arrangement, though here absolute positioning will be used for each */
            width: 200px;
            /* Container width for positioning */
            height: 200px;
            /* Container height for positioning */
        }

        .circle-1,
        .circle-2,
        .circle-3,
        .circle-4 {
            position: absolute;
        }

        .circle-1 {
            /* Large top-left circle of the group - now half circle at the top */
            background-color: #3B6241;
            /* Dark green */
            width: 150px;
            height: 75px;
            /* Half the height */
            top: 0px;
            /* Push to the top */
            left: auto;
            /* Let right positioning handle horizontal */
            right: 150px;
            /* Adjust horizontal position */
            border-radius: 0 0 75px 75px;
            /* Round only bottom corners */
        }

        .circle-2 {
            /* Large top-right circle of the group */
            background-color: #9ACC4A;
            /* Light green */
            width: 100px;
            height: 100px;
            top: 20px;
            /* Adjust vertical position */
            right: 50px;
            border-radius: 70%;
        }

        .circle-3 {
            /* Medium bottom-left circle of the group */
            background-color: #3B6241;
            /* Dark green */
            width: 40px;
            height: 40px;
            top: 130px;
            left: auto;
            /* Adjust horizontal position */
            right: 170px;
            border-radius: 70%;
        }

        .circle-4 {
            /* Small bottom-right circle of the group */
            background-color: #9ACC4A;
            /* Dark green */
            width: 25px;
            height: 25px;
            top: 180px;
            right: 80px;
            border-radius: 70%;
        }

        /* Adjust input field padding for icon */
        .input-with-icon {
            padding-left: 2.5rem !important;
            /* Adjust based on icon size */
        }

        .input-group-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            /* gray-400 */
        }

        .toggle-password-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            /* gray-400 */
            cursor: pointer;
        }
    </style>
</head>

<body class="relative bg-gray-100 min-h-screen flex flex-col items-center justify-center overflow-hidden">

    <div class="absolute top-0 left-0 h-full z-0">
        <svg viewBox="100 280 600 800" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <path d="M0,0 L100,930 C120,250 830,0 520,0" fill="#9ACC4A" />
            <path d="M0,0 L100,920 C100,200 820,0 500,0" fill="#3B6241" />
        </svg>
    </div>
    <div class="circle-1"></div>


    <div class="top-right-circles">
        <div class="circle-1"></div>
        <div class="circle-2"></div>
        <div class="circle-3"></div>
        <div class="circle-4"></div>
    </div>


    <div class="absolute bottom-0 right-0 w-80 h-full ">
        <svg viewBox="0 0 500 100" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
            <path d="M500,800 L500,0 C600,10 500,500 0,800 Z" fill="#3B6241" />
            <path d="M500,800 L500,0 C590,90 490,490 50,800 Z" fill="#9ACC4A" />
        </svg>
    </div>

    <div class="relative z-20 flex flex-col items-center p-4">
        <img alt="Logo" class="mb-10 h-48 w-48 md:h-56 md:w-56" src="{{ asset('images/logo2.png') }}" />


        <h1 class="text-3xl font-bold text-gray-800 mb-8">ทะเบียนออนไลน์</h1>

        <div class="bg-gray-200 shadow-md rounded-2xl px-8 py-8 w-80 md:w-96">
            <h2 class="text-2xl font-semibold text-gray-700 text-center mb-6">เข้าสู่ระบบ</h2>

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                <div class="relative">
                    <span class="input-group-icon"><i class="fas fa-user"></i></span>
                    <input type="email" name="email" placeholder="youremail@gmail.com"
                        class="input-with-icon w-full bg-white rounded-lg px-4 py-3 border border-gray-300 outline-none focus:border-green-500 text-gray-700 text-base"
                        required>
                </div>
                <div class="relative">
                    <span class="input-group-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" id="passwordInput" name="password" placeholder="•••••••••"
                        class="input-with-icon w-full bg-white rounded-lg px-4 py-3 border border-gray-300 outline-none focus:border-green-500 text-gray-700 text-base"
                        required>
                    <span class="toggle-password-icon" id="togglePassword"><i class="fas fa-eye"></i></span>
                </div>
                {{-- <div class="flex justify-between text-sm text-gray-600 px-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" class="rounded text-green-600 focus:ring-green-500 h-4 w-4" checked>
                        <span>บันทึกรหัส</span>
                    </label>
                    <a href="#" class="text-green-600 hover:underline">ลืมรหัสผ่าน</a>
                </div> --}}
                <button type="submit"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-3 rounded-xl shadow-md transition duration-300 flex items-center justify-center space-x-2">
                    <span>เข้าสู่ระบบ</span>
                    <!-- <i class="fas fa-arrow-right"></i> -->
                </button>
            </form>
        </div>
    </div>
    <p class="mt-10">Copyright 2025 รุ่น 1.0.0</p>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('passwordInput');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            // Toggle icon
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    </script>
</body>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if ($errors->has('email'))
    <script>
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถเข้าสู่ระบบได้',
            text: '{{ $errors->first('email') }}'
        });
    </script>
@endif

</html>
