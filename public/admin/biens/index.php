<td>
    <div class="btn-group" role="group">
        <a href="/admin/biens/edit/<?php echo $bien['id']; ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-pencil"></i> Modifier
        </a>
        <a href="/admin/biens/detail?id=<?php echo $bien['id']; ?>" class="btn btn-sm btn-info">
            <i class="bi bi-eye"></i> Voir
        </a>
        <button type="button" class="btn btn-sm btn-danger" 
                onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce bien ?')) window.location.href='/admin/biens/delete/<?php echo $bien['id']; ?>'">
            <i class="bi bi-trash"></i> Supprimer
        </button>
    </div>
</td> 