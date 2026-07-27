export async function addTicket(title, description, location, type, priority, assigned, status) {
    const userData = {
        title: title,
        description: description,
        location: location,
        type: type,
        priority: priority,
        assigned: assigned,
        status: status
    };

    try {
        const response = await fetch('api/add_ticket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(userData)
        });

        // Tjek om HTTP-statuskoden er ok (f.eks. 200 OK)
        if (!response.ok) {
            throw new Error(`HTTP fejl! Status: ${response.status}`);
        }

        const result = await response.json();
        return result;

    } catch (error) {
        // Returner false (eller kast fejlen videre), så din handleCreateUserSubmit ved det gik galt
        throw new Error('Fejl i addTicket API-kald: ' + error);
    }
}