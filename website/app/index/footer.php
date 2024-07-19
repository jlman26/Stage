<!-- Footer -->
<footer class="bg-custom text-light text-center py-3 mt-auto">
    &copy; 2024 Trivia Master. All rights reserved.
</footer>

<!-- JavaScript -->
<script>
    document.querySelectorAll('.background-button').forEach(button => {
        let animationFrameId;

        button.addEventListener('mousemove', (e) => {
            const rect = button.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width * 100;
            const y = (e.clientY - rect.top) / rect.height * 100;

            cancelAnimationFrame(animationFrameId);
            animationFrameId = requestAnimationFrame(() => {
                button.style.backgroundPosition = `${x}% ${y}%`;
            });
        });

        button.addEventListener('mouseleave', () => {
            cancelAnimationFrame(animationFrameId);
            animationFrameId = requestAnimationFrame(() => {
                button.style.backgroundPosition = 'center';
            });
        });
    });
</script>
</body>
</html>
