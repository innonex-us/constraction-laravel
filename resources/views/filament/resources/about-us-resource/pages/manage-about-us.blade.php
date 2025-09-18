<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center">
                    <x-heroicon-o-information-circle class="w-5 h-5 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">About Us Page</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Manage your company's About Us page content</p>
                </div>
            </div>
            
            <form wire:submit="save">
                {{ $this->form }}
                
                <div class="mt-6 flex gap-3">
                    {{ $this->saveAction }}
                    {{ $this->previewAction }}
                </div>
            </form>
        </div>
        
        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-light-bulb class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5 flex-shrink-0" />
                <div class="text-sm">
                    <p class="font-medium text-blue-900 dark:text-blue-100 mb-1">Quick Tips:</p>
                    <ul class="text-blue-800 dark:text-blue-200 space-y-1">
                        <li>• Use the rich text editor to format your content with headers, lists, and links</li>
                        <li>• Add a hero image to make your About Us page more visually appealing</li>
                        <li>• Fill in meta title and description for better SEO</li>
                        <li>• Use the Preview button to see how your page looks before saving</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
