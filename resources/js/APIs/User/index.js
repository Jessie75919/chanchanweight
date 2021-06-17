const BASE_URL = '/weight';

export const emailExists = (email) => {
    return axios.post(`${BASE_URL}/user/emailExists`, {email})
        .then(({ data: { data } }) => data);
};
