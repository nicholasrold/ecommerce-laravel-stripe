import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const images = [
        '/img/bg-1.jpg',
        '/img/bg-2.jpg',
        '/img/bg-3.jpg',
        '/img/bg-4.jpg',
        '/img/bg-5.jpg'
    ];

    let current = 0;
    const slide1 = document.getElementById('bg-1');
    const slide2 = document.getElementById('bg-2');
    
    // Set initial background
    slide1.style.backgroundImage = `url('${images[0]}')`;
    slide1.classList.add('active');

    function changeBG() {
        const nextIndex = (current + 1) % images.length;
        const activeSlide = current % 2 === 0 ? slide1 : slide2;
        const nextSlide = current % 2 === 0 ? slide2 : slide1;

        // Load the next image onto the inactive slide
        nextSlide.style.backgroundImage = `url('${images[nextIndex]}')`;
        
        // Reset scale for the zoom animation loop
        nextSlide.style.transform = 'scale(1.2)';
        
        setTimeout(() => {
            // Execute transition
            nextSlide.style.opacity = '1';
            nextSlide.style.transform = 'scale(1)'; // Zoom in to original scale
            activeSlide.style.opacity = '0';
        }, 50);

        current++;
    }

    // Change background every 6 seconds (calmer pace)
    setInterval(changeBG, 6000);
});