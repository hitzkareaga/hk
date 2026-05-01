document.addEventListener('DOMContentLoaded', function () {

    const presetButtons = document.querySelectorAll('.wpsct-preset-btn');
    const toggles = document.querySelectorAll('.wpsct-toggle input');
    const detailToggles = document.querySelectorAll('.wpsct-info-toggle, .wpsct-title-toggle');

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

    function bindDetailToggles() {

        detailToggles.forEach(btn => {

            btn.addEventListener('click', function () {

                const targetId = this.getAttribute('aria-controls');
                const target = targetId ? document.getElementById(targetId) : null;

                if (!target) return;

                const isExpanded = this.getAttribute('aria-expanded') === 'true';
                const nextExpanded = !isExpanded;

                syncDetailToggleState(targetId, nextExpanded);
                target.hidden = !nextExpanded;
            });
        });
    }

    function syncDetailToggleState(targetId, isExpanded) {

        document
            .querySelectorAll('[aria-controls="' + targetId + '"]')
            .forEach(control => {
                control.setAttribute('aria-expanded', String(isExpanded));
            });
    }

    function bindModals() {

        document.addEventListener('click', function (event) {

            const openControl = event.target.closest('[data-wpsct-modal-open]');

            if (!openControl) return;

            const modalId = openControl.getAttribute('data-wpsct-modal-open');
            const modal = modalId ? document.getElementById(modalId) : null;

            if (!modal) return;

            modal.hidden = false;
            document.body.classList.add('wpsct-modal-open');

            const closeButton = modal.querySelector('[data-wpsct-modal-close]');

            if (closeButton) {
                closeButton.focus();
            }
        });

        document.addEventListener('click', function (event) {

            const closeControl = event.target.closest('[data-wpsct-modal-close]');

            if (!closeControl) return;

            const modal = closeControl.closest('.wpsct-modal');

            if (!modal) return;

            closeModal(modal);
        });

        document.addEventListener('keydown', function (event) {

            if (event.key !== 'Escape') return;

            const modal = document.querySelector('.wpsct-modal:not([hidden])');

            if (modal) {
                closeModal(modal);
            }
        });
    }

    function closeModal(modal) {

        modal.hidden = true;
        document.body.classList.remove('wpsct-modal-open');
    }

    function syncInitialState() {
        setActivePresetUI(currentPreset);
    }

    // INIT
    bindToggleChanges();
    bindPresetButtons();
    bindDetailToggles();
    bindModals();
    syncInitialState();

});
