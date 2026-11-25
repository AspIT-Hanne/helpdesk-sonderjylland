export async function updateTicketNote(id, content) {
    
    const noteData = {
        id: id,
        text: content
    };

    try {
        const response = await fetch('api/update_ticketnote.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(noteData)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
                console.log('API result: ' + result);
        return result;

    } catch (error) {
        console.error('Fejl i updateTicketNote API-kald:', error);
        return false;
    }
}