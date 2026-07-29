export async function deleteSettings(table, id) {
   
    const data = {
        table: table,
        id: id
    };

    try {
        const response = await fetch('api/delete_settings.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (!response.ok && !result.error) {
            return { success: false, error: `HTTP fejl! Status: ${response.status}` };
        }

        return result;

    } catch (error) {
        return { success: false, error: 'Netværksfejl eller ugyldigt svar: ' + error.message };
    }
}