const BASE_URL = '/weight';

export const getWeightState = (date) => {
    return axios.get(`${BASE_URL}/weightStates`, { params: { date } })
        .then(({ data }) => data);
};
export const weightStatesByMonth = ({ year, month }) => {
    return axios.get(`${BASE_URL}/weightStatesByMonth`,
        { params: { year, month } })
        .then(({ data }) => data);
};
