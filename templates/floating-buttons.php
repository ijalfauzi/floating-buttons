<div id="floatingButtons" class="fixed right-4 flex flex-col space-y-2 z-50 transition-all duration-300 ease-in-out bottom-4">
    <button id="backToTopBtn" class="group flex items-center justify-center bg-akg-green text-akg-light-green p-2 sm:p-3 rounded-md text-base font-semibold hover:bg-akg-dark-green transition duration-300 transform hover:scale-105 shadow-lg opacity-0">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:-translate-y-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>
    <button id="whatsappBtn" class="group flex items-center justify-center bg-akg-green text-akg-light-green p-2 sm:p-3 rounded-md text-base font-semibold hover:bg-akg-dark-green transition duration-300 transform hover:scale-105 shadow-lg">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="currentColor" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
    </button>
</div>

<div id="formPopup" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50 p-4 backdrop-blur-sm">
    <div class="bg-gray-100 p-6 sm:p-8 rounded-lg shadow-md w-full max-w-md relative">
        <button id="closeForm" class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <div class="text-center mb-6">
            <h2 class="text-2xl sm:text-3xl font-semibold text-akg-dark-green mb-2">WhatsApp Chat</h2>
            <p class="text-gray-600 text-sm sm:text-base">Please provide your details to start a WhatsApp conversation with us.</p>
        </div>
        <form id="contactForm" class="space-y-4">
            <input type="text" 
                   id="name" 
                   name="name" 
                   placeholder="Name" 
                   class="w-full px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-akg-green focus:border-transparent transition duration-300 placeholder-gray-400" 
                   required>

            <input type="email" 
                   id="email" 
                   name="email" 
                   placeholder="Email" 
                   class="w-full px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-akg-green focus:border-transparent transition duration-300 placeholder-gray-400" 
                   required>

            <input type="tel" 
                   id="phone" 
                   name="phone" 
                   placeholder="Phone" 
                   class="w-full px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-akg-green focus:border-transparent transition duration-300 placeholder-gray-400" 
                   required>

            <input type="text" 
                   id="company" 
                   name="company" 
                   placeholder="Company" 
                   class="w-full px-4 py-2 sm:py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-akg-green focus:border-transparent transition duration-300 placeholder-gray-400" 
                   required>
           
            <div class="flex items-center">
                <input type="checkbox" 
                       id="verifyData" 
                       name="verify_data" 
                       class="form-checkbox h-4 w-4 sm:h-5 sm:w-5 text-akg-green rounded focus:ring-akg-green" 
                       required>
                <label for="verifyData" class="ml-2 sm:ml-3 text-gray-700 text-xs sm:text-sm">
                    I verify that the information provided above is correct
                </label>
            </div>
           
            <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                <button type="submit" 
                        id="submitButton" 
                        class="w-full bg-akg-green text-white px-4 py-2 sm:py-3 rounded-lg hover:bg-akg-dark-green transition duration-300 disabled:opacity-50 disabled:cursor-not-allowed text-sm sm:text-base font-semibold flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Start Chat
                </button>
                <button type="button" 
                        id="clearForm" 
                        class="w-full bg-gray-200 text-gray-800 px-4 py-2 sm:py-3 rounded-lg hover:bg-gray-300 transition duration-300 text-sm sm:text-base font-semibold flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 sm:h-5 sm:w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Clear Form
                </button>
            </div>
        </form>
    </div>
</div>