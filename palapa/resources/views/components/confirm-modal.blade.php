<!-- Custom Alpine Confirmation Modal -->
<div x-data="{ 
        isOpen: false, 
        message: '', 
        formToSubmit: null,
        confirm() {
            if (this.formToSubmit) {
                this.formToSubmit.submit();
            }
            this.isOpen = false;
        },
        cancel() {
            this.isOpen = false;
            this.formToSubmit = null;
        }
    }" 
    @open-confirm-modal.window="
        isOpen = true; 
        message = $event.detail.message; 
        formToSubmit = $event.detail.form;
    "
    x-show="isOpen" 
    style="display: none;" 
    class="relative z-[100]" 
    aria-labelledby="modal-title" 
    role="dialog" 
    aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="isOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
         style="background-color: rgba(45, 55, 72, 0.7); position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 100;"></div>

    <div class="fixed inset-0 z-[101] w-screen overflow-y-auto" style="position: fixed; top: 0; right: 0; bottom: 0; left: 0; z-index: 101;">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0" style="display: flex; min-height: 100%; align-items: center; justify-content: center; padding: 16px;">
            
            <!-- Modal Panel -->
            <div x-show="isOpen"
                 @click.away="cancel()"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                 style="background: #ffffff; border-radius: 16px; padding: 24px; max-width: 400px; width: 100%; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: scale(1); transition: all 0.3s ease;">
                
                <div class="sm:flex sm:items-start" style="display: flex; gap: 16px; align-items: flex-start;">
                    <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10" style="width: 48px; height: 48px; border-radius: 50%; background: #fee2e2; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="ph ph-warning-circle" style="color: #dc2626; font-size: 24px;"></i>
                    </div>
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left" style="margin-top: 0; text-align: left;">
                        <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title" style="margin: 0 0 8px 0; font-size: 18px; color: #111827;">Konfirmasi Tindakan</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500" x-text="message" style="margin: 0; font-size: 14px; color: #4b5563; line-height: 1.5;"></p>
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse" style="display: flex; gap: 12px; margin-top: 24px; justify-content: flex-end;">
                    <button type="button" @click="confirm()" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto" style="background: #dc2626; color: white; border: none; border-radius: 8px; padding: 10px 16px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                        Ya, Lanjutkan
                    </button>
                    <button type="button" @click="cancel()" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto" style="background: white; color: #374151; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px 16px; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
