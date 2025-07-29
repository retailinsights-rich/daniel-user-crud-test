import axios from 'axios'

const instance = axios.create({
    withCredentials: true,
    headers: {
        Accept: 'application/json',
    },
})

export default instance