document.addEventListener('DOMContentLoaded', function () {

    // Base hook para futuras features PRO
    console.log('WPSCT admin loaded');

});

document.addEventListener('DOMContentLoaded', function () {

    const presetButtons = document.querySelectorAll('.wpsct-preset-btn');
    const checkboxes = document.querySelectorAll('.wpsct-toggle input');

    const customPreset = document.querySelector('[value="custom"]');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', () => {

            if (!customPreset) return;

            presetButtons.forEach(btn => btn.classList.remove('active'));
            customPreset.classList.add('active');

        });
    });

});

document.addEventListener('DOMContentLoaded', function () {

    const buttons = document.querySelectorAll('.wpsct-preset-btn');
    const panel = document.getElementById('wpsct-preset-info');

    buttons.forEach(btn => {

        btn.addEventListener('click', function (e) {

            // allow form submit but animate UI first
            panel.classList.add('is-fading');

            setTimeout(() => {
                panel.classList.remove('is-fading');
            }, 250);

        });

    });

});