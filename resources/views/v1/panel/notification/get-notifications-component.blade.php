<flux:dropdown align="end" wire:click="getNotifications">
    <flux:button variant="subtle" square class="group relative" aria-label="{{ __('panel.notifications') }}">
        <flux:icon.bell variant="mini" class="text-zinc-500 dark:text-white" />
        @if($unread_notifications > 0)
            <div
                id="unread_notifications"
                class="absolute  bg-red-500 text-white text-xs font-medium py-1 px-1 rounded-full min-w-5 h-5 flex items-center justify-center"
                style="font-size: 9px;top: calc(var(--spacing) * -0.5);right: calc(var(--spacing) * -0.1);">
                {{ $unread_notifications > 99 ? '99+' : $unread_notifications }}
            </div>
        @else
            <div id="unread_notifications" class="hidden"></div>
        @endif
    </flux:button>

    <flux:menu class="p-0 w-80" keep-open>
        <!-- Header -->
        <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center justify-between">
                <flux:heading size="sm">{{ __('panel.notifications') }}</flux:heading>
                <flux:button
                    variant="subtle"
                    size="xs"
                    class="text-xs"
                    id="mark-as-read-all-notifications"
                    wire:click="markAsReadAllNotifications">
                    {{ __('panel.mark_all_as_read') }}
                </flux:button>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="max-h-80 overflow-y-auto">
            <div id="notifications-list">
                <!-- Notifications will be populated here by JavaScript -->
            </div>
        </div>

        <!-- Footer -->
        <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
            <!-- Loading Spinner -->
            <div
                id="notification-spinner"
                class="justify-center items-center py-2 hidden">
                <flux:icon.arrow-path class="w-4 h-4 animate-spin" />
                <span class="ml-2 text-sm text-zinc-500">{{ __('panel.loading') }}...</span>
            </div>

            <!-- Load More Button -->
            <flux:button
                id="get-more-notifications"
                variant="subtle"
                class="w-full hidden"
                size="sm"
                wire:click="getMoreNotifications">
                {{ __('panel.view_more_notifications') }}
            </flux:button>
        </div>
    </flux:menu>
</flux:dropdown>

@assets
    <script type="module" src="https://www.gstatic.com/firebasejs/8.2.7/firebase-app.js"></script>
    <script type="module" src="https://www.gstatic.com/firebasejs/8.2.7/firebase-firestore.js"></script>
@endassets

@script
<script>
    $wire.on('list-notifications', (data) => {
        const notifications = data[0];
        const spinner = document.getElementById('notification-spinner');
        const get_more_notifications = document.getElementById('get-more-notifications');
        const list = document.getElementById('notifications-list');

        // Show spinner
        spinner.classList.remove('hidden');
        spinner.classList.add('flex');

        const current_page = notifications.current_page;
        const last_page = notifications.last_page;
        const notifications_data = notifications.data;

        // Create and add notification elements
        if(notifications_data.length == 0){
            const div = document.createElement('div');
            div.classList.add('px-4', 'py-3', 'text-center', 'text-zinc-500', 'dark:text-zinc-400');
            div.innerHTML = '{{ __("panel.no_notifications") }}';
            list.appendChild(div);
        } else {
            notifications_data.forEach(notification => {
                const div = document.createElement('div');
                div.classList.add('px-4', 'py-3', 'hover:bg-zinc-50', 'dark:hover:bg-zinc-800', 'cursor-pointer', 'border-b', 'border-zinc-100', 'dark:border-zinc-700', 'last:border-b-0');

                // Unread indicator is handled by the blue dot only

                div.addEventListener('click', function() {
                    $wire.doAction(notification);
                });

                const flexDiv = document.createElement('div');
                flexDiv.classList.add('flex', 'items-start', 'gap-3');

                const content = document.createElement('div');
                content.classList.add('flex-1', 'min-w-0');

                const headerDiv = document.createElement('div');
                headerDiv.classList.add('flex', 'items-center', 'justify-between');

                const title = document.createElement('div');
                title.classList.add('text-sm');
                if (notification.read === "N") {
                    title.classList.add('font-medium', 'text-zinc-900', 'dark:text-white');
                } else {
                    title.classList.add('text-zinc-600', 'dark:text-zinc-300');
                }
                title.textContent = notification.title;

                const time = document.createElement('div');
                time.classList.add('text-xs', 'text-zinc-400');
                time.textContent = notification.formatted_date;

                headerDiv.appendChild(title);
                headerDiv.appendChild(time);

                const message = document.createElement('div');
                message.classList.add('text-sm', 'text-zinc-500', 'dark:text-zinc-400', 'mt-1', 'line-clamp-2');
                message.textContent = notification.message;

                content.appendChild(headerDiv);
                content.appendChild(message);
                flexDiv.appendChild(content);

                if (notification.read === "N") {
                    const dot = document.createElement('div');
                    dot.classList.add('flex-shrink-0', 'mt-1');

                    const animatedContainer = document.createElement('span');
                    animatedContainer.classList.add('relative', 'flex', 'h-3', 'w-3');

                    const pingDot = document.createElement('span');
                    pingDot.classList.add('animate-ping', 'absolute', 'inline-flex', 'h-full', 'w-full', 'rounded-full', 'bg-blue-400', 'opacity-75');

                    const innerDot = document.createElement('span');
                    innerDot.classList.add('relative', 'inline-flex', 'rounded-full', 'h-3', 'w-3', 'bg-blue-500', 'unread-notification-alert');

                    animatedContainer.appendChild(pingDot);
                    animatedContainer.appendChild(innerDot);
                    dot.appendChild(animatedContainer);
                    flexDiv.appendChild(dot);
                }

                div.appendChild(flexDiv);
                list.appendChild(div);
            });
        }

        // Hide spinner
        spinner.classList.add('hidden');
        spinner.classList.remove('flex');

        // Show/hide load more button
        if (current_page < last_page) {
            get_more_notifications.classList.remove('hidden');
        } else {
            get_more_notifications.classList.add('hidden');
        }
    });

    $wire.on('update-notifications-count', (data) => {
        // Handle notification count updates
    });

    $wire.on('mark-as-read-all-notifications', () => {
        // Remove unread indicators
        const unread_notifications = document.querySelectorAll('.unread-notification-alert');
        unread_notifications.forEach(notification => {
            notification.classList.remove('unread-notification-alert', 'bg-blue-500');
            notification.classList.add('hidden');
        });

        const counter = document.getElementById('unread_notifications');
        counter.textContent = "0";
        counter.classList.add('hidden');
    });

    $wire.on('new-notification', (data) => {
        console.log("here");
        // Add new notification to top of list
        if(!data[0].first_load){
            const notification = data[0].notification;
            const list = document.getElementById('notifications-list');

            const div = document.createElement('div');
            div.classList.add('px-4', 'py-3', 'hover:bg-zinc-50', 'dark:hover:bg-zinc-800', 'cursor-pointer', 'border-b', 'border-zinc-100', 'dark:border-zinc-700');

            div.addEventListener('click', function() {
                $wire.doAction(notification);
            });

            const flexDiv = document.createElement('div');
            flexDiv.classList.add('flex', 'items-start', 'gap-3');

            const content = document.createElement('div');
            content.classList.add('flex-1', 'min-w-0');

            const headerDiv = document.createElement('div');
            headerDiv.classList.add('flex', 'items-center', 'justify-between');

            const title = document.createElement('div');
            title.classList.add('text-sm', 'font-medium', 'text-zinc-900', 'dark:text-white');
            title.textContent = notification.title;

            const time = document.createElement('div');
            time.classList.add('text-xs', 'text-zinc-400');
            time.textContent = notification.formatted_date;

            headerDiv.appendChild(title);
            headerDiv.appendChild(time);

            const message = document.createElement('div');
            message.classList.add('text-sm', 'text-zinc-500', 'dark:text-zinc-400', 'mt-1', 'line-clamp-2');
            message.textContent = notification.message;

            content.appendChild(headerDiv);
            content.appendChild(message);
            flexDiv.appendChild(content);

            const dot = document.createElement('div');
            dot.classList.add('flex-shrink-0', 'mt-1');

            const animatedContainer = document.createElement('span');
            animatedContainer.classList.add('relative', 'flex', 'h-3', 'w-3');

            const pingDot = document.createElement('span');
            pingDot.classList.add('animate-ping', 'absolute', 'inline-flex', 'h-full', 'w-full', 'rounded-full', 'bg-blue-400', 'opacity-75');

            const innerDot = document.createElement('span');
            innerDot.classList.add('relative', 'inline-flex', 'rounded-full', 'h-3', 'w-3', 'bg-blue-500', 'unread-notification-alert');

            animatedContainer.appendChild(pingDot);
            animatedContainer.appendChild(innerDot);
            dot.appendChild(animatedContainer);
            flexDiv.appendChild(dot);

            div.appendChild(flexDiv);
            list.insertBefore(div, list.firstChild);
        }

        // Update notification counter
        const unread_notifications = data[0].unread_notifications;
        const counter = document.getElementById('unread_notifications');
        if (unread_notifications > 0) {
            counter.textContent = unread_notifications > 99 ? '99+' : unread_notifications;
            counter.classList.remove('hidden');
            // Añadir las clases de estilo del contador original
            counter.className = 'absolute bg-red-500 text-white text-xs font-medium py-1 px-1 rounded-full min-w-5 h-5 flex items-center justify-center';
            counter.style.cssText = 'font-size: 9px;top: calc(var(--spacing) * -0.5);right: calc(var(--spacing) * -0.1);';
        } else {
            counter.classList.add('hidden');
        }
    });

    // Firebase configuration
    const firebaseConfig = {
        apiKey: "{{ env('FIREBASE_API_KEY') }}",
        authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
        projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
        appId: "{{ env('FIREBASE_APP_ID') }}",
        measurementId: "{{ env('FIREBASE_MEASUREMENT_ID') }}"
    };

    try {
        const notificationApp = firebase.initializeApp(firebaseConfig, "notificationApp");
        var db = notificationApp.firestore();
    } catch (e) {
        const notificationApp = firebase.app("notificationApp");
        var db = notificationApp.firestore();
    }

    var isFirstSnapshot = true;
    const adminId = "{{ auth('admin')->id() }}";

    db.collection("admin-notification-trigger").doc(adminId)
    .onSnapshot(function(doc) {
        if (doc.exists) {
            const data = doc.data();

            // Skip only the very first snapshot to avoid loading existing data
            if (isFirstSnapshot) {
                isFirstSnapshot = false;
                return;
            }

            // Call Livewire method for every subsequent change
            @this.call('loadNewNotification', data.notification_id);
            console.log('New notification detected:');
        }
    }, function(error) {
        console.error('Error listening to document:', error);
    });
</script>
@endscript
