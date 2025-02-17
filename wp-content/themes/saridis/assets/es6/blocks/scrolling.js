const scrolling = () => {
    try {
        const links = document.querySelectorAll('[href^="#"]'),
              speed = 0.15;

        const goingTo = (hash) => {
            let widthTop = document.documentElement.scrollTop,
                toBlock = document.querySelector(hash).getBoundingClientRect().top - 50,
                start = null;

            requestAnimationFrame(step);

            function step(time) {
                if (start === null) {
                    start = time;
                }

                let progress = time - start,
                    r = (toBlock < 0 ? Math.max(widthTop - progress/speed, widthTop + toBlock) : Math.min(widthTop + progress/speed, widthTop + toBlock));

                    document.documentElement.scrollTo(0, r);

                if (r != widthTop + toBlock) {
                    requestAnimationFrame(step);
                } else {
                    //location.hash = hash;
                }
            }
        }

        if (window.location.hash) goingTo(window.location.hash);
        
        links.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();

                goingTo(this.hash);
            });
        });
    } catch (e) {
        console.log(e.stack);
    }
};

export default scrolling;