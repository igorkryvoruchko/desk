import axios from "axios";

const baseDomain = "http://localhost";
const baseURL = `${baseDomain}/api/`; // Incase of /api/v1;

// ALL DEFUALT CONFIGURATION HERE

export default axios.create({
  baseURL,
  headers: {
    Authorization: "Bearer ",
  },
});
