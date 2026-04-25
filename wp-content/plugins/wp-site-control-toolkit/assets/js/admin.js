document.addEventListener('DOMContentLoaded', function () {

    const presetButtons = document.querySelectorAll('.wpsct-preset-btn');
    const toggles = document.querySelectorAll('.wpsct-toggle input');

    let currentPreset = getActivePreset();

    function getActivePreset() {
        const active = document.querySelector('.wpsct-preset-btn.active');
        return active ? active.value : 'custom';
    }

    function setActivePresetUI(presetKey) {

        presetButtons.forEach(btn => {
            btn.classList.toggle('active', btn.value === presetKey);
        });
    }

    function switchToCustom() {

        if (currentPreset === 'custom') return;

        currentPreset = 'custom';

        setActivePresetUI('custom');

        updatePresetTitle('Custom');
    }

    function updatePresetTitle(title) {

        const titleEl = document.querySelector('.wpsct-preset-active-title');

        if (titleEl) {
            titleEl.innerText = title;
        }
    }

    function bindToggleChanges() {

        toggles.forEach(toggle => {

            toggle.addEventListener('change', function () {

                // cualquier cambio manual => custom
                switchToCustom();
            });
        });
    }

    function bindPresetButtons() {

        presetButtons.forEach(btn => {

            btn.addEventListener('click', function () {

                const presetKey = this.value;

                currentPreset = presetKey;

                setActivePresetUI(presetKey);

                updatePresetTitle(this.textContent.trim());

                // reset UI animation
                const infoBox = document.querySelector('.wpsct-preset-info');

                if (infoBox) {
                    infoBox.classList.add('is-fading');

                    setTimeout(() => {
                        infoBox.classList.remove('is-fading');
                    }, 200);
                }
            });
        });
    }

    function syncInitialState() {
        setActivePresetUI(currentPreset);
    }

    // INIT
    bindToggleChanges();
    bindPresetButtons();
    syncInitialState();

});