export async function addTicketnote(text, ticket_id) {
    const ticketNoteData = {
        text: text,
        ticket_id: ticket_id
    };

    try {
        const response = await fetch('api/add_ticketnote.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(ticketNoteData)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
        return result;

    } catch (error) {
        console.error('Fejl i addTicketnote API-kald:', error);
        return false;
    }
}