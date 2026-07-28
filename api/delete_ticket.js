export async function deleteTicket(id) {
   
    const data = {
        id: id
    };

    try {
        const response = await fetch('api/delete_ticket.php', {
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
        return result;

    } catch (error) {
        alert('Der opstod en uventet fejl: ' + error.message);
        return false;
    }
}