document.addEventListener("DOMContentLoaded", () => {
    const players = document.querySelectorAll(".playlist-block");

    players.forEach(playerWrapper => {
        const audio = playerWrapper.querySelector(".audio-player__element");
        const playBtn = playerWrapper.querySelector(".audio-player__btn--play");
        const prevBtn = playerWrapper.querySelector(".audio-player__btn--prev");
        const nextBtn = playerWrapper.querySelector(".audio-player__btn--next");
        const toggleListBtn = playerWrapper.querySelector(".audio-player__btn--toggle-list");

        const iconPlay = playBtn.querySelector(".icon-play");
        const iconPause = playBtn.querySelector(".icon-pause");

        const seekBar = playerWrapper.querySelector(".audio-player__seek-bar");
        const currentTimeEl = playerWrapper.querySelector(".audio-player__time--current");
        const durationEl = playerWrapper.querySelector(".audio-player__time--total");

        const volumeSlider = playerWrapper.querySelector(".audio-player__volume-slider");
        const tracklistContainer = playerWrapper.querySelector(".audio-player__tracklist");
        const tracks = playerWrapper.querySelectorAll(".audio-player__track-item");

        const titleEl = playerWrapper.querySelector(".audio-player__title");
        const descEl = playerWrapper.querySelector(".audio-player__desc");

        let currentTrackIndex = 0;
        let isPlaying = false;

        if (tracks.length === 0) return; // Nothing to do

        // Format time (seconds -> MM:SS)
        const formatTime = (time) => {
            if (isNaN(time)) return "0:00";
            const min = Math.floor(time / 60);
            const sec = Math.floor(time % 60);
            return `${min}:${sec < 10 ? '0' : ''}${sec}`;
        };

        // Load specific track
        const loadTrack = (index) => {
            if (index < 0 || index >= tracks.length) return;

            const track = tracks[index];
            const src = track.getAttribute("data-src");
            const title = track.getAttribute("data-title");
            const desc = track.getAttribute("data-desc");

            if (!src || src === '#') return; // Skip dummy data in preview

            audio.src = src;
            titleEl.textContent = title;
            descEl.textContent = desc;
            currentTrackIndex = index;

            // Update UI list
            tracks.forEach(t => t.classList.remove("is-playing"));
            track.classList.add("is-playing");

            // Allow time to load metadata
            audio.load();
        };

        const playAudio = () => {
            if (audio.src) {
                audio.play();
                isPlaying = true;
                iconPlay.style.display = "none";
                iconPause.style.display = "block";
            }
        };

        const pauseAudio = () => {
            audio.pause();
            isPlaying = false;
            iconPlay.style.display = "block";
            iconPause.style.display = "none";
        };

        // Event Listeners

        playBtn.addEventListener("click", () => {
            if (isPlaying) {
                pauseAudio();
            } else {
                playAudio();
            }
        });

        prevBtn.addEventListener("click", () => {
            currentTrackIndex--;
            if (currentTrackIndex < 0) currentTrackIndex = tracks.length - 1;
            loadTrack(currentTrackIndex);
            if (isPlaying) playAudio();
        });

        nextBtn.addEventListener("click", () => {
            currentTrackIndex++;
            if (currentTrackIndex >= tracks.length) currentTrackIndex = 0;
            loadTrack(currentTrackIndex);
            if (isPlaying) playAudio();
        });

        // Loop automatically when ended
        audio.addEventListener("ended", () => {
            nextBtn.click();
        });

        // Update seek bar natively
        audio.addEventListener("timeupdate", () => {
            if (audio.duration) {
                const progressPercent = (audio.currentTime / audio.duration) * 100;
                seekBar.value = progressPercent;
                currentTimeEl.textContent = formatTime(audio.currentTime);
            }
        });

        // Load metadata (duration)
        audio.addEventListener("loadedmetadata", () => {
            durationEl.textContent = formatTime(audio.duration);
        });

        // Seek manually
        seekBar.addEventListener("input", (e) => {
            const seekTime = (e.target.value / 100) * audio.duration;
            audio.currentTime = seekTime;
        });

        // Volume control
        volumeSlider.addEventListener("input", (e) => {
            audio.volume = e.target.value;
        });

        // Toggle tracklist
        toggleListBtn.addEventListener("click", () => {
            const isHidden = tracklistContainer.style.display === "none";
            tracklistContainer.style.display = isHidden ? "block" : "none";
            toggleListBtn.classList.toggle("is-active");
            toggleListBtn.setAttribute("aria-expanded", isHidden ? "true" : "false");
        });

        // Click on individual tracks
        tracks.forEach((track, index) => {
            track.addEventListener("click", () => {
                loadTrack(index);
                playAudio();
            });
        });

        // Initialize first track
        loadTrack(currentTrackIndex);
    });
});
