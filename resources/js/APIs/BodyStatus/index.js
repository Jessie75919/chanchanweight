const BASE_URL = '/weight';

export const getStateCategories = () => {
    return axios.get(`${BASE_URL}/status/categories`)
        .then(({ data: { data } }) => data);
};

export const getUserSelectedStatusByDate = (date) => {
    return axios.get(`${BASE_URL}/status/selected`, { params: { date } })
        .then(({ data: { data } }) => data);
};
