</main>

</div>
<script>
    function updateDashboardScrollHeight() {
        const scrollContainer = document.getElementById('dashboardScroll');
        if (!scrollContainer || !document.body.classList.contains('page-dashboard')) {
            return;
        }

        const nav = document.querySelector('nav');
        const navHeight = nav ? nav.offsetHeight : 0;
        const topOffset = Math.max(navHeight + 24, 120);
        document.documentElement.style.setProperty('--dashboard-scroll-top', `${topOffset}px`);
    }

    function toggleHeaderPanel(panelId, trigger) {
        const panel = document.getElementById(panelId);
        if (!panel) {
            return;
        }

        const isHidden = panel.classList.contains('hidden');
        const otherPanelId = panelId === 'searchPanel' ? 'mainNav' : 'searchPanel';
        const otherPanel = document.getElementById(otherPanelId);

        if (isHidden && otherPanel && !otherPanel.classList.contains('hidden')) {
            otherPanel.classList.add('hidden');
            const otherTrigger = document.querySelector(`[aria-controls="${otherPanelId}"]`);
            if (otherTrigger) {
                otherTrigger.setAttribute('aria-expanded', 'false');
            }
        }

        panel.classList.toggle('hidden');

        if (trigger) {
            trigger.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        }

        if (panelId === 'searchPanel' && !panel.classList.contains('hidden')) {
            const input = panel.querySelector('input[type="search"]');
            if (input) {
                window.setTimeout(() => input.focus(), 0);
            }
        }

        updateDashboardScrollHeight();
    }

    window.addEventListener('resize', updateDashboardScrollHeight);
    window.addEventListener('load', updateDashboardScrollHeight);
    updateDashboardScrollHeight();
</script>
</body>

</html>
