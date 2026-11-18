export async function deleteUser(id) {
   
    const userData = {
        id: id
    };

    try {
        const response = await fetch('api/delete_user.php', {
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
        alert('Der opstod en uventet fejl: ' + error);
        return false;
    }
}