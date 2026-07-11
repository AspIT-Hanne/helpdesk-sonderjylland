export async function fetchSettings() {
    try {
        const response = await fetch('api/get_settings.php');
        if (!response.ok) throw new Error('Kunne ikke hente settings');
        return await response.json();
    } catch (error) {
        console.error("API Fejl:", error);
        return null;
    }
}