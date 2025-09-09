@extends('layouts.app')

<section class="hero-section">
    <div class="video-container">
        <video id="bgVideo" autoplay loop muted playsinline>
            <source src="https://v1.pinimg.com/videos/iht/720p/d9/f0/73/d9f073c6cc0901abdba0f28e5e59d58c.mp4" type="video/mp4">
        </video>
    </div>

    <div class="hero-overlay"></div>

    <div class="content-container">
        <h1 class="hero-text neon-text">
            Xora <br> 
        </h1>
        <p class="hero-text">
           a modern online shop that makes shopping easy, fast, and convenient.
        </p>
    </div>



    <script>
        // Video fallback handling
        document.addEventListener('DOMContentLoaded', function() {
            const video = document.getElementById('bgVideo');
            const fallback = document.querySelector('.video-fallback');

            video.addEventListener('error', function() {
                console.log('Video failed to load, showing fallback');
                video.style.display = 'none';
                fallback.style.display = 'block';
            });

            video.addEventListener('loadstart', function() {
                fallback.style.display = 'none';
            });
        });
    </script>
</section>
