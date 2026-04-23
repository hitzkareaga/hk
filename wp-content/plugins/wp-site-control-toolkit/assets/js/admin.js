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