import React, { useEffect, useState } from 'react';
import axios from "axios";
import { Routing } from '../../routes';
import { DragDropContext, Droppable, Draggable } from "react-beautiful-dnd";

const projectRating = document.querySelector('.js-project');
const token = projectRating.dataset.token;

const Table = () => {

  const [projects, setProjects] = useState([]);

  useEffect(() => {
    axios
      .get('/admin/projects/json')
      .then(result => setProjects(result.data));
  }, []);

  useEffect(() => {
    saveOnDrop();
  }, [projects]);

  const reorder = (list, startIndex, endIndex) => {
    const result = Array.from(list);
    const [removed] = result.splice(startIndex, 1);
    result.splice(endIndex, 0, removed);
    
    return result;
  };

  const onDragEnd = result => {
    if (!result.destination) {
      return;
    }

    const newItems = reorder(
      projects,
      result.source.index,
      result.destination.index
    );

    setProjects(newItems);
  }

  const saveOnDrop = () => {
    axios
      .post('/admin/projects/move', {projects: projects})
      .then(r => {if(r.status === 204) {console.log("success")}})
  }

  return (
    <DragDropContext onDragEnd={onDragEnd}>
      <table cellSpacing="0" className="table-projects-list">
      <thead>
        <tr>
          <th>Name</th>
          <th>Actions</th>
        </tr>
      </thead>
        <Droppable droppableId="droppable">
          {(provided) => (
            <tbody
              {...provided.droppableProps}
              ref={provided.innerRef}
            >
              {projects.map((project, index) => (
                <Draggable key={project.id} draggableId={"item-" + project.id} index={index}>
                  {(provided) => (
                    <tr ref={provided.innerRef}
                        {...provided.draggableProps}
                        {...provided.dragHandleProps}
                        key={project.id}
                    >
                      <td><a href={Routing.generate('admin_project_edit', { id: project.id })}>{project.title}</a></td>
                      <td>
                        <a href={Routing.generate('admin_project_edit', { id: project.id })}><i className="fas fa-pen"></i></a>
                        <button
                          className="project-delete-button"
                          onClick={() => {
                            if (
                              window.confirm('Are you sure you want to delete this project?'))
                              location.replace(Routing.generate('admin_project_delete', { id: project.id, token: token })
                            )
                          }}>
                          <i className="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  )}
                </Draggable>
              ))}
              {provided.placeholder}
            </tbody>
          )}
        </Droppable>
      </table>
    </DragDropContext>
  )
}

export default Table;
