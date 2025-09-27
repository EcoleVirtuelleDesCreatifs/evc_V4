<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EVC WebTV - Vos cours et masterclass en direct et à la demande</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0a0a0a;
            color: #e2e8f0;
        }
        .evc-orange {
            color: #FF750F;
        }
        .playlist-item.active {
            background-color: rgba(255, 117, 15, 0.1);
            border-left-color: #FF750F;
        }
    </style>
</head>
<body class="antialiased">

    <!-- Header -->
    <header class="bg-gray-900/80 backdrop-blur-lg sticky top-0 z-20">
        <nav class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
            <a href="{{ route('homepage') }}">
                <img src="{{ asset('assets/img/logo.png') }}" alt="EVC Logo" class="h-24">
            </a>
            <a href="{{ route('homepage') }}" class="btn-cta text-white font-bold py-3 px-6 rounded-full">Retour à l'accueil</a>
        </nav>
    </header>

    <main class="mx-auto max-w-7xl p-6 lg:px-8 mt-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Player & Info -->
            <div class="lg:col-span-2">
                <!-- Player -->
                <div class="aspect-w-16 aspect-h-9 w-full bg-black rounded-lg overflow-hidden shadow-2xl">
                    <iframe id="main-player" src="https://www.youtube.com/embed/{{ $videos[0]['id'] }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
                <!-- Info -->
                <div class="mt-6">
                    <h1 id="video-title" class="text-3xl font-bold tracking-tight text-white sm:text-4xl">{{ $videos[0]['title'] }}</h1>
                    <p id="video-speaker" class="mt-2 text-lg font-semibold text-orange-400">Par {{ $videos[0]['speaker'] }}</p>
                    <p id="video-description" class="mt-4 text-lg leading-8 text-gray-300">{{ $videos[0]['description'] }}</p>
                </div>
            </div>

            <!-- Playlist -->
            <div class="lg:col-span-1">
                <div class="bg-gray-900/80 p-6 rounded-2xl border border-gray-800">
                    <h2 class="text-xl font-bold text-white mb-4">Playlist</h2>
                    <div id="playlist" class="space-y-2 max-h-[70vh] overflow-y-auto">
                        @foreach ($videos as $index => $video)
                            <div class="playlist-item p-4 rounded-lg cursor-pointer border-l-4 border-transparent hover:bg-gray-800 transition-colors duration-300 {{ $index == 0 ? 'active' : '' }}" 
                                 data-video-id="{{ $video['id'] }}" 
                                 data-title="{{ $video['title'] }}" 
                                 data-speaker="{{ $video['speaker'] }}" 
                                 data-description="{{ $video['description'] }}">
                                <h4 class="font-semibold text-white">{{ $video['title'] }}</h4>
                                <p class="text-sm text-gray-400">{{ $video['speaker'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const mainPlayer = document.getElementById('main-player');
            const videoTitle = document.getElementById('video-title');
            const videoSpeaker = document.getElementById('video-speaker');
            const videoDescription = document.getElementById('video-description');
            const playlistItems = document.querySelectorAll('.playlist-item');

            playlistItems.forEach(item => {
                item.addEventListener('click', function() {
                    // Get data from clicked item
                    const videoId = this.dataset.videoId;
                    const title = this.dataset.title;
                    const speaker = this.dataset.speaker;
                    const description = this.dataset.description;

                    // Update main player
                    mainPlayer.src = `https://www.youtube.com/embed/${videoId}`;

                    // Update info
                    videoTitle.textContent = title;
                    videoSpeaker.textContent = `Par ${speaker}`;
                    videoDescription.textContent = description;

                    // Update active state in playlist
                    document.querySelector('.playlist-item.active').classList.remove('active');
                    this.classList.add('active');
                });
            });
        });
    </script>

</body>
</html>
