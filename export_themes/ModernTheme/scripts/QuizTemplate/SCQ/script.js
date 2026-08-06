 document.addEventListener("DOMContentLoaded", () => {
	
        fetch('question.json')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
				mainData=data;
               // console.log('JSON data loaded:', data);
                // Set the iframe source based on the JSON data
                const iframeSrc = data.question.iframeSrc;
               // console.log('Setting iframe src to:', iframeSrc);

                // Create iframe element
                const iframe = document.createElement('iframe');
                iframe.id = 'contentFrame';
                iframe.src = iframeSrc;
                iframe.style.width = '100%';
                iframe.style.height = '100%';
                iframe.style.border = 'none';

                // Append iframe to the container
                document.getElementById('contentContainer').appendChild(iframe);
            })
            .catch(error => console.error('Error loading JSON data:', error));
    });


