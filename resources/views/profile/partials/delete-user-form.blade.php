<section class="space-y-6">
    <header>
        <h2 class="text-lg font-black text-slate-800 uppercase tracking-tight">
            {{ __('Deactivate Account') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500 font-medium">
            {{ __('Once your account is deactivated, you will be logged out and your profile will be hidden. However, all your data and order history will be safely preserved. You can reactivate your account at any time by simply logging back in with your credentials.') }}
        </p>
    </header>

    <x-danger-button
        data-open-modal="confirm-user-deletion"
        class="!rounded-2xl !py-4 !px-8 !font-black !uppercase !tracking-widest !text-[10px]"
    >{{ __('Deactivate Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8 bg-white rounded-[2rem]">
            @csrf
            @method('delete')

            <h2 class="text-xl font-black text-slate-800 uppercase tracking-tight">
                {{ __('Are you sure you want to deactivate?') }}
            </h2>
            
            <p class="mt-3 text-sm text-slate-500 font-medium leading-relaxed">
                {{ __('Your account will be temporarily disabled. All your resources and data will be kept safe, and you can restore everything instantly by logging back in later. Please enter your password to confirm deactivation.') }}
            </p>

            <div class="mt-8">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full !rounded-2xl !border-slate-100 !bg-slate-50 !py-4 !px-6"
                    placeholder="{{ __('Enter your password to confirm deactivation') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <x-secondary-button data-close-modal class="!rounded-2xl !py-4 !px-8 !font-black !uppercase !tracking-widest !text-[10px] !border-slate-100 !bg-slate-50">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="!rounded-2xl !py-4 !px-8 !font-black !uppercase !tracking-widest !text-[10px]">
                    {{ __('Deactivate Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
