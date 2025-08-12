<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Portal Karawang - Loading...</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #059669 100%);
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            perspective: 1000px;
        }

        .splash-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 1rem;
        }

        .text-container {
            position: relative;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            transform-style: preserve-3d;
            max-width: 90%;
            margin: 0 auto;
        }

        .letter {
            font-size: clamp(1.5rem, 4vw, 3.5rem);
            font-weight: 700;
            color: #ffffff;
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
            opacity: 0;
            transform: translateZ(-80px) rotateX(90deg) scale(0.5);
            animation: letterAppear 0.7s ease-out forwards;
            position: relative;
        }

        .letter::before {
            content: attr(data-letter);
            position: absolute;
            top: 0;
            left: 0;
            color: rgba(255, 255, 255, 0.25);
            transform: translateZ(-15px);
            filter: blur(1.5px);
        }

        @keyframes letterAppear {
            0% {
                opacity: 0;
                transform: translateZ(-80px) rotateX(90deg) scale(0.5);
            }

            50% {
                opacity: 0.9;
                transform: translateZ(15px) rotateX(-5deg) scale(1.05);
            }

            100% {
                opacity: 1;
                transform: translateZ(0) rotateX(0deg) scale(1);
            }
        }

        .subtitle {
            font-size: clamp(0.8rem, 2vw, 1.2rem);
            color: rgba(255, 255, 255, 0.85);
            margin-top: 25px;
            opacity: 0;
            animation: fadeInUp 1.2s ease-out 3s forwards;
            text-align: center;
            max-width: 80%;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(25px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .loading-dots {
            display: flex;
            gap: 8px;
            margin-top: 15px;
            opacity: 0;
            animation: fadeInUp 1.2s ease-out 3.5s forwards;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.75);
            border-radius: 50%;
            animation: pulse 1.8s ease-in-out infinite;
        }

        .dot:nth-child(2) {
            animation-delay: 0.3s;
        }

        .dot:nth-child(3) {
            animation-delay: 0.6s;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
                opacity: 0.75;
            }

            50% {
                transform: scale(1.4);
                opacity: 1;
            }
        }

        .fade-out {
            animation: fadeOut 1.2s ease-out forwards;
        }

        @keyframes fadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }

            to {
                opacity: 0;
                transform: scale(0.95);
            }
        }

        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: -1;
        }

        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 50%;
            animation: float 7s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.6;
            }

            50% {
                transform: translateY(-80px) rotate(180deg);
                opacity: 0.25;
            }
        }

        .glow-effect {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: clamp(200px, 40vw, 300px);
            height: clamp(200px, 40vw, 300px);
            background: radial-gradient(circle, rgba(255, 255, 255, 0.08) 0%, transparent 70%);
            border-radius: 50%;
            animation: rotate 25s linear infinite;
            z-index: -1;
        }

        @keyframes rotate {
            from {
                transform: translate(-50%, -50%) rotate(0deg);
            }

            to {
                transform: translate(-50%, -50%) rotate(360deg);
            }
        }

        /* Media queries for better responsiveness */
        @media (max-width: 768px) {
            .text-container {
                gap: 4px;
            }

            .letter {
                font-size: clamp(1.2rem, 6vw, 2.5rem);
            }

            .subtitle {
                margin-top: 20px;
            }

            .loading-dots {
                gap: 6px;
                margin-top: 10px;
            }

            .dot {
                width: 8px;
                height: 8px;
            }
        }

        @media (max-width: 480px) {
            .text-container {
                max-width: 95%;
            }

            .subtitle {
                font-size: clamp(0.7rem, 3vw, 1rem);
            }
        }
    </style>
</head>

<body>
    <div class="splash-container">
        <div class="particles" id="particles"></div>
        <div class="glow-effect"></div>

        <div class="text-container">
            <span class="letter" data-letter="W" style="animation-delay: 0.1s;">W</span>
            <span class="letter" data-letter="e" style="animation-delay: 0.2s;">e</span>
            <span class="letter" data-letter="b" style="animation-delay: 0.3s;">b</span>
            <span style="width: 15px;"></span>
            <span class="letter" data-letter="P" style="animation-delay: 0.5s;">P</span>
            <span class="letter" data-letter="o" style="animation-delay: 0.6s;">o</span>
            <span class="letter" data-letter="r" style="animation-delay: 0.7s;">r</span>
            <span class="letter" data-letter="t" style="animation-delay: 0.8s;">t</span>
            <span class="letter" data-letter="a" style="animation-delay: 0.9s;">a</span>
            <span class="letter" data-letter="l" style="animation-delay: 1.0s;">l</span>
            <span style="width: 15px;"></span>
            <span class="letter" data-letter="K" style="animation-delay: 1.2s;">K</span>
            <span class="letter" data-letter="a" style="animation-delay: 1.3s;">a</span>
            <span class="letter" data-letter="r" style="animation-delay: 1.4s;">r</span>
            <span class="letter" data-letter="a" style="animation-delay: 1.5s;">a</span>
            <span class="letter" data-letter="w" style="animation-delay: 1.6s;">w</span>
            <span class="letter" data-letter="a" style="animation-delay: 1.7s;">a</span>
            <span class="letter" data-letter="n" style="animation-delay: 1.8s;">n</span>
            <span class="letter" data-letter="g" style="animation-delay: 1.9s;">g</span>
        </div>

        <p class="subtitle">Portal Informasi Digital Kabupaten Karawang</p>

        <div class="loading-dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <script>
        // Create floating particles with responsive count
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            let particleCount = 20;
            if (window.innerWidth < 768) {
                particleCount = 10;
            } else if (window.innerWidth < 1024) {
                particleCount = 15;
            }

            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';

                const size = Math.random() * 3 + 2;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';

                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 7 + 's';
                particle.style.animationDuration = (Math.random() * 4 + 4) + 's';

                particlesContainer.appendChild(particle);
            }
        }

        // Initialize particles
        createParticles();

        // Handle splash screen completion
        setTimeout(() => {
            const splashContainer = document.querySelector('.splash-container');
            splashContainer.classList.add('fade-out');

            setTimeout(() => {
                window.location.href = "{{ route('home') }}";
            }, 1200);
        }, 6000);
    </script>
</body>

</html>