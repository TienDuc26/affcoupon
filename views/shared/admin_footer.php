            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/js/admin.js"></script>
    
    <script>
        // Sidebar Toggle logic
        $(document).ready(function() {
            $('.menu-btn').on('click', function() {
                $('.sidebar').toggleClass('collapsed');
                $('.main-content').toggleClass('expanded');
            });
            
            // Close sidebar on mobile
            $('.close-sidebar-btn').on('click', function() {
                $('.sidebar').removeClass('collapsed');
                $('.main-content').removeClass('expanded');
            });
            
            // Submenu Toggle
            $('.submenu-toggle').on('click', function() {
                $(this).parent().toggleClass('open');
            });
        });
    </script>
</body>
</html>
