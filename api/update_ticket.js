export async function updateTicket(rawid, title, type, description, place, status, priority, assigned) {
    const id = parseInt(rawid.replace('#', ''), 10);
    
    const data = {
        id: id,
        title: title,
        type: type,
        description: description,
        place: place,
        status: status,
        priority: priority,
        assigned: assigned
    };

    try {
        const response = await fetch('api/update_ticket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
                console.log('API result: ' + result);
        return result;

    } catch (error) {
        console.error('Fejl i updateTicket API-kald:', error.message);
        return false;
    }
}