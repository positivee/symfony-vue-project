
<template>
    <h1>Submited Forms</h1>
    <div v-if="forms.length">
        <table>
            <thead>
                <tr>
                    <th>Imię </th>
                    <th>Nazwisko</th>
                    <th>Email</th>
                    <th>Wiadomość</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="form in forms" :key="form.id">
                    <td>{{ form.name }}</td>
                    <td>{{ form.surname }}</td>
                    <td>{{ form.email }}</td>
                    <td>{{ form.message }}</td>
                </tr>
            </tbody>
        </table>

    </div>
    <div v-else>
        Loading
    </div>
    <p v-if="error">{{ error }}</p>
</template>


<script setup lang="ts">
import { onMounted, ref } from 'vue';

interface FormData {
    id: number;
    name: string;
    surname: string;
    email: string;
    message: string;
}

const forms = ref<FormData[]>([]);
let error = ref('');


onMounted(() => {
    fetch('http://127.0.0.1:8000/forms'
    ).then((response) => response.json())
        .then((data) => { forms.value = data; })
        .catch((err) => err.value = err.message);
})

</script>
