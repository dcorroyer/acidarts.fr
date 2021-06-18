import React, { useEffect, useState } from 'react';
import axios from "axios";

const Table = () => {

  const [projects, setProjects] = useState([]);
  useEffect(() => {
    axios.get('/admin/projects/json').then(result => setProjects(result.data));
  }, []);

  return (
    <table cellspacing="0" class="table-projects-list">
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
            <td><a href="#">{project.title}</a></td>
            <td>
              <a href="#"><i class="fas fa-pen"></i></a>
              <form method="post" action="{{ path('admin_project_delete', {id: project.id}) }}" class="delete-project" onsubmit="return confirm('Are you sure ?')">
                <input type="hidden" name="_method" value="DELETE" />
                <input type="hidden" name="_token" value="{{ csrf_token('delete' ~ project.id) }}" />
                <button type="submit"><i class="fas fa-trash"></i></button>
              </form>
            </td>
          </tr>
        )}
      </tbody>
    </table>
  )
}

export default Table;
