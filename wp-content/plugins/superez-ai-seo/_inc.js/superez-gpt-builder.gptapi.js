
    // get_response requires global: g_GPT which is a ChatGPTClient object
    
    class ChatGPTClient {
        constructor(apiKey) {
          this.apiKey = apiKey;
          this.baseUrl = 'https://api.openai.com/v1/chat/completions';
        }
  
        sendRequest(model, messages, temperature = 0.7, max_tokens=150) {
  
          const headers = {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${this.apiKey}`,
          };
  
          const requestData = {
            model,
            messages,
            temperature,
            max_tokens
          };
  
          // return $.ajax({
          // use jQuery. syntax to make it easier to use in a web browser
          return jQuery.ajax({
            type: 'POST',
            url: this.baseUrl,
            headers,
            data: JSON.stringify(requestData),
            success: (response) => response,
            error: (error) => error,
          }); // end -> ajax
  
        } // end -> sendRequest
      } // end -> class ChatGPTClient
  
      // requires a global: g_GPT
      async function get_response(model,messages,temperature, max_tokens=40, update_class='#error')
      {
          // check for blank g_GPT.apiKey
          if(g_GPT.apiKey == '')
          {
              // $(update_class).val('Error: No API key set.');
              alert('Error: No API key set.');
              return;
          }
  
          try {
              const response = await g_GPT.sendRequest(model, messages, temperature, max_tokens);
              console.log(response);
              assistantResponse = response.choices[0].message.content;
  
              // remove double quotes from beginning and end of string
              if(assistantResponse.startsWith('"') && assistantResponse.endsWith('"'))
                  assistantResponse = assistantResponse.substring(1, assistantResponse.length - 1);
              
              jQuery(update_class).val(assistantResponse);
          } catch (error) {
              // $(update_class).val(`Error: ${error.statusText}`);
              // use jquery syntax to make it easier to use in a web browser
              // jQuery(update_class).val(`Error: ${error.statusText}`);
              console.log('ERROR:', error.statusText);
              alert('ERROR:', error.statusText);
          }
  
          /* sleep for 5 seconds to help prevent overloading */
          await new Promise(r => setTimeout(r, 5000));
  
          return;
      }
    