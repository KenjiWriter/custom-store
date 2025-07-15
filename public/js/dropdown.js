        // User Dropdown functionality
        function toggleUserDropdown() {
            const dropdown = document.querySelector('.user-dropdown');
            const menu = document.getElementById('userDropdownMenu');

            if (dropdown && menu) {
                dropdown.classList.toggle('active');

                // Close when clicking outside
                if (dropdown.classList.contains('active')) {
                    setTimeout(() => {
                        document.addEventListener('click', closeDropdownOutside);
                    }, 0);
                } else {
                    document.removeEventListener('click', closeDropdownOutside);
                }
            }
        }

        function closeDropdownOutside(event) {
            const dropdown = document.querySelector('.user-dropdown');
            const menu = document.getElementById('userDropdownMenu');

            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('active');
                document.removeEventListener('click', closeDropdownOutside);
            }
        }

        // Close dropdown on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const dropdown = document.querySelector('.user-dropdown');
                if (dropdown && dropdown.classList.contains('active')) {
                    dropdown.classList.remove('active');
                    document.removeEventListener('click', closeDropdownOutside);
                }
            }
        });
