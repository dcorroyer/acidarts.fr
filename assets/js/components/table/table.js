import React, { useEffect, useState } from 'react';
import axios from "axios";

const Table = () => {

    const [projects, setProjects] = useState([]);
    useEffect(() => {
        axios.get('/admin/projects/json').then(result => setProjects(result.data));
    }, []);

    return (<ul>{projects && projects.map(project => <li key={project.id}>{project.title}</li>)}</ul>)
}

export default Table;