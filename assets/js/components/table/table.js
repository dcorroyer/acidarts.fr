import React, { useEffect, useState } from 'react';
import axios from "axios";
import { Routing } from '../../routes';

const projectRating = document.querySelector('.js-project');
const token = projectRating.dataset.token;

const Table = () => {

  const [projects, setProjects] = useState([]);

  useEffect(() => {
    axios
      .get('/admin/projects/json')
      .then(result => setProjects(result.data));
  }, []);

  return (
    <table cellSpacing="0" className="table-projects-list">
      <thead>
        <tr>
          <th>Position</th>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        {projects && projects.map(project =>
          <tr key={project.id}>
            <td>{project.position}</td>
            <td><a href={Routing.generate('admin_project_edit', { id: project.id })}>{project.title}</a></td>
            <td>
              <a href={Routing.generate('admin_project_edit', { id: project.id })}><i class="fas fa-pen"></i></a>
              <button
                className="project-delete-button"
                onClick={() => {
                  if (
                    window.confirm('Are you sure you want to delete this project?'))
                    location.replace(Routing.generate('admin_project_delete', { id: project.id, token: token })
                  )
                }}>
                <i class="fas fa-trash"></i>
              </button>
            </td>
          </tr>
        )}
      </tbody>
    </table>
  )
}

export default Table;
