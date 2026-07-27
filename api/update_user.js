export async function addUser(name, email, role, password) {
    const userData = {
        name: name,
        email: email,
        role: role,
        password: password
    };

    try {
        const response = await fetch('api/add_user.php', {
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
        console.error('Fejl i addUser API-kald:', error);
        // Returner false (eller kast fejlen videre), så din handleCreateUserSubmit ved det gik galt
        return false;
    }
}