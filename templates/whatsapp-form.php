<!-- templates/whatsapp-form.php -->
<div class="relative">
    <button id="closeForm" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-semibold text-green-700 mb-2">WhatsApp Chat</h2>
        <p class="text-gray-600 text-sm">Please provide your details to start a WhatsApp conversation with us.</p>
    </div>
    
    <form id="contactForm" class="space-y-4">
        <input type="text" 
               id="name" 
               name="name" 
               placeholder="Name" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-300 placeholder-gray-400">
        
        <input type="email" 
               id="email" 
               name="email" 
               placeholder="Email" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-300 placeholder-gray-400">
        
        <input type="tel" 
               id="phone" 
               name="phone" 
               placeholder="Phone" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-300 placeholder-gray-400">
        
        <input type="text" 
               id="company" 
               name="company" 
               placeholder="Company" 
               required
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-300 placeholder-gray-400">
        
        <div class="flex items-center">
            <input type="checkbox" 
                   id="verifyData" 
                   name="verify_data" 
                   required
                   class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
            <label for="verifyData" class="ml-2 text-sm text-gray-600">
                I verify that the information provided above is correct
            </label>
        </div>
        
        <div class="flex space-x-2">
            <button type="submit"
                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 flex items-center justify-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654z"/>
                </svg>
                <span>Start Chat</span>
            </button>
            <button type="button" 
                    id="clearForm"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition duration-300 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </button>
        </div>
    </form>
</div>