export async function getTicketNotes(ticketId) {
    console.log('test', ticketId);
    try {
        const response = await fetch(`${window.BASE_URL}api/get_ticketnotes.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            credentials: 'include',
            body: JSON.stringify({ ticketId })
        });

        if (!response.ok) throw new Error('Kunne ikke hente sagsnoter');
        return await response.json();
    } catch (error) {
        console.error("API Fejl:", error);
        return null;
    }
}