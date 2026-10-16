<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Team Members</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
        }
        h1 {
            color: #667eea;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 10px 15px;
            border: 2px solid #e9ecef;
            border-radius: 5px;
            font-size: 1em;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            font-weight: bold;
            background: #667eea;
            color: white;
            margin-right: 10px;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-back {
            background: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
        }
        th {
            background: #f8f9fa;
            font-weight: bold;
        }
        .status-active {
            color: #198754;
            font-weight: bold;
        }
        .status-inactive {
            color: #6c757d;
            font-weight: bold;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 0.85em;
        }
        .btn-active {
            background: #198754;
        }
        .btn-inactive {
            background: #6c757d;
        }
        .btn-danger {
            background: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>👥 Manage Team Members</h1>
        
        <form id="memberForm">
            <div class="form-group">
                <label for="memberName">Name *</label>
                <input type="text" id="memberName" required>
            </div>
            
            <div class="form-group">
                <label for="memberEmail">Email</label>
                <input type="email" id="memberEmail">
            </div>
            
            <button type="submit" class="btn">Add Member</button>
            <button type="button" class="btn btn-back" onclick="window.location.href='index.html'">Back to Tasks</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="membersList">
            </tbody>
        </table>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', loadMembers);
        document.getElementById('memberForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('action', 'add_member');
            formData.append('name', document.getElementById('memberName').value);
            formData.append('email', document.getElementById('memberEmail').value);
            
            try {
                const response = await fetch('ajax/team_api.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if(data.success) {
                    alert('Member added successfully!');
                    document.getElementById('memberForm').reset();
                    loadMembers();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(error) {
                console.error('Error:', error);
                alert('Error adding member');
            }
        });
        async function loadMembers() {
            try {
                const response = await fetch('ajax/team_api.php?action=get_members');
                const data = await response.json();
                if(data.success) {
                    const tbody = document.getElementById('membersList');
                    tbody.innerHTML = '';
                    
                    data.members.forEach(member => {
                        const row = document.createElement('tr');
                        const isActive = Number(member.status) === 1;
                        row.innerHTML = `
                            <td>${member.name}</td>
                            <td>${member.email || '-'}</td>
                            <td class="${isActive ? 'status-active' : 'status-inactive'}">
                                ${isActive ? 'Active' : 'Inactive'}
                            </td>
                            <td>
                                <button class="btn btn-active btn-sm" onclick="updateMemberStatus(${member.id}, 1)">Active</button>
                                <button class="btn btn-inactive btn-sm" onclick="updateMemberStatus(${member.id}, 0)">Inactive</button>
                            </td>
                        `;
                        tbody.appendChild(row);
                    });
                }
            } catch(error) {
                console.error('Error loading members:', error);
            }
        }
        async function updateMemberStatus(id, status) {
            const formData = new FormData();
            formData.append('action', 'update_status');
            formData.append('id', id);
            formData.append('status', status);

            try {
                const response = await fetch('ajax/team_api.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if(data.success) {
                    loadMembers();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch(error) {
                console.error('Error updating member status:', error);
                alert('Error updating member status');
            }
        }
    </script>
</body>
</html>
