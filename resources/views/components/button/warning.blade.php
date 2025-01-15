<button {{ $attributes->merge(['type' => 'submit', 'class' => '
            inline-flex items-center
            px-4 py-2
            bg-amber-200 dark:bg-red-200
            hover:bg-amber-600 dark:hover:bg-red-600 dark:hover:text-white
            border border border-amber-100 border-transparent rounded-md
            font-semibold text-xs uppercase tracking-widest
            text-amber-600 hover:text-white dark:text-red-800
            focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-red-800
            transition ease-in-out duration-150
            active:bg-amber-900 dark:active:bg-amber-300
            ']) }}>
    {{ $slot }}
</button>
