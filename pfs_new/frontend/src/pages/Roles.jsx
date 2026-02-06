import { useEffect, useState } from 'react';
import { useRolesStore } from '../lib/stores/rolesStore';
import { useUIStore } from '../lib/stores/uiStore';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '../components/ui/card';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '../components/ui/table';
import { Button } from '../components/ui/button';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle, DialogTrigger } from '../components/ui/dialog';
import { AlertDialog, AlertDialogAction, AlertDialogCancel, AlertDialogContent, AlertDialogDescription, AlertDialogFooter, AlertDialogHeader, AlertDialogTitle } from '../components/ui/alert-dialog';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { Skeleton } from '../components/ui/skeleton';
import { Badge } from '../components/ui/badge';
import EmptyState from '../components/EmptyState';
import { FaPlus, FaBriefcase, FaEdit, FaTrash } from 'react-icons/fa';
import { t } from '../lib/translations/index';

export default function Roles() {
  const { locale } = useUIStore();
  const { roles, loading, fetchRoles, createRole, updateRole, deleteRole } = useRolesStore();
  const [open, setOpen] = useState(false);
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [roleToDelete, setRoleToDelete] = useState(null);
  const [editingRole, setEditingRole] = useState(null);
  const [formData, setFormData] = useState({
    name: ''
  });

  useEffect(() => {
    fetchRoles();
  }, [fetchRoles]);

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      if (editingRole) {
        await updateRole(editingRole.id, formData);
      } else {
        await createRole(formData);
      }
      setOpen(false);
      setEditingRole(null);
      setFormData({ name: '' });
      fetchRoles();
    } catch (error) {
      console.error('Error saving role:', error);
      alert(error.message || 'Failed to save role');
    }
  };

  const handleEdit = (role) => {
    setEditingRole(role);
    setFormData({ name: role.name });
    setOpen(true);
  };

  const handleDeleteClick = (id) => {
    setRoleToDelete(id);
    setDeleteDialogOpen(true);
  };

  const handleDeleteConfirm = async () => {
    if (!roleToDelete) return;
    
    try {
      await deleteRole(roleToDelete);
      setDeleteDialogOpen(false);
      setRoleToDelete(null);
      fetchRoles();
    } catch (error) {
      console.error('Error deleting role:', error);
      alert(error.message || 'Failed to delete role');
      setDeleteDialogOpen(false);
      setRoleToDelete(null);
    }
  };

  const handleNew = () => {
    setEditingRole(null);
    setFormData({ name: '' });
    setOpen(true);
  };

  return (
    <div className="space-y-4 sm:space-y-6">
      <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
        <div>
          <h1 className="text-2xl sm:text-3xl lg:text-4xl font-bold tracking-tight">{t(locale, 'admin.roles.title')}</h1>
          <p className="text-sm sm:text-base text-muted-foreground mt-1 sm:mt-2">{t(locale, 'admin.roles.description')}</p>
        </div>
        <Dialog open={open} onOpenChange={setOpen}>
          <DialogTrigger asChild>
            <Button onClick={handleNew} className="gap-2 w-full sm:w-auto min-h-[44px] touch-manipulation">
              <FaPlus className="h-4 w-4" />
              {t(locale, 'admin.roles.addRole')}
            </Button>
          </DialogTrigger>
          <DialogContent className="w-[95vw] sm:w-full max-w-md mx-2 sm:mx-0">
            <DialogHeader>
              <DialogTitle className="text-lg sm:text-xl">
                {editingRole
                  ? t(locale, 'admin.roles.editRole')
                  : t(locale, 'admin.roles.addRole')}
              </DialogTitle>
              <DialogDescription className="text-sm">
                {t(locale, 'admin.roles.formDescription')}
              </DialogDescription>
            </DialogHeader>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div className="space-y-2">
                <Label className="text-sm sm:text-base">{t(locale, 'admin.roles.roleName')}</Label>
                <Input
                  value={formData.name}
                  onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                  placeholder="e.g., admin"
                  required
                  className="min-h-[44px] touch-manipulation text-base sm:text-sm"
                />
              </div>
              <DialogFooter className="flex-col sm:flex-row gap-2 sm:gap-0 pt-2">
                <Button 
                  type="button" 
                  variant="outline" 
                  onClick={() => setOpen(false)}
                  className="w-full sm:w-auto min-h-[44px] touch-manipulation order-2 sm:order-1"
                >
                  {t(locale, 'admin.common.cancel')}
                </Button>
                <Button 
                  type="submit"
                  className="w-full sm:w-auto min-h-[44px] touch-manipulation order-1 sm:order-2"
                >
                  {editingRole
                    ? t(locale, 'admin.common.update')
                    : t(locale, 'admin.common.create')}
                </Button>
              </DialogFooter>
            </form>
          </DialogContent>
        </Dialog>
      </div>

      <Card>
        <CardHeader className="p-4 sm:p-6">
          <CardTitle className="flex items-center gap-2 text-lg sm:text-xl">
            <FaBriefcase className="h-4 w-4 sm:h-5 sm:w-5" />
            {t(locale, 'admin.roles.roleList')}
          </CardTitle>
          <CardDescription className="text-sm">
            {t(locale, 'admin.roles.allRoles')}
          </CardDescription>
        </CardHeader>
        <CardContent className="p-4 sm:p-6">
          {loading ? (
            <div className="space-y-3">
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
              <Skeleton className="h-12 w-full" />
            </div>
          ) : roles.length === 0 ? (
            <EmptyState
              title={t(locale, 'admin.roles.noRoles')}
              description={t(locale, 'admin.roles.noRolesDescription')}
            />
          ) : (
            <div className="rounded-md border overflow-x-auto -mx-4 sm:mx-0">
              <div className="min-w-full inline-block">
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead className="min-w-[60px]">ID</TableHead>
                      <TableHead className="min-w-[200px]">Role Name</TableHead>
                      <TableHead className="min-w-[100px]">Status</TableHead>
                      <TableHead className="text-right min-w-[180px]">Actions</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {roles.map((role) => (
                      <TableRow key={role.id} className="hover:bg-muted/50">
                        <TableCell className="font-medium">{role.id}</TableCell>
                        <TableCell>
                          <div className="flex items-center gap-2">
                            <FaBriefcase className="h-4 w-4 text-primary flex-shrink-0" />
                            <span className="font-medium capitalize truncate">{role.name}</span>
                          </div>
                        </TableCell>
                        <TableCell>
                          <Badge variant="secondary">Active</Badge>
                        </TableCell>
                        <TableCell>
                          <div className="flex items-center justify-end gap-2 flex-wrap">
                            <Button 
                              variant="outline" 
                              size="sm" 
                              className="gap-1 min-h-[36px] touch-manipulation text-xs sm:text-sm"
                              onClick={() => handleEdit(role)}
                            >
                              <FaEdit className="h-3 w-3" />
                              <span className="hidden sm:inline">{t(locale, 'admin.users.edit')}</span>
                              <span className="sm:hidden">Edit</span>
                            </Button>
                            <Button 
                              variant="destructive" 
                              size="sm" 
                              className="gap-1 min-h-[36px] touch-manipulation text-xs sm:text-sm"
                              onClick={() => handleDeleteClick(role.id)}
                            >
                              <FaTrash className="h-3 w-3" />
                              <span className="hidden sm:inline">{t(locale, 'admin.common.delete')}</span>
                              <span className="sm:hidden">Del</span>
                            </Button>
                          </div>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            </div>
          )}
        </CardContent>
      </Card>

      <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
        <AlertDialogContent className="w-[95vw] sm:w-full mx-2 sm:mx-0">
          <AlertDialogHeader>
            <AlertDialogTitle className="text-lg sm:text-xl">{t(locale, 'admin.common.confirm')}</AlertDialogTitle>
            <AlertDialogDescription className="text-sm">
              {t(locale, 'admin.roles.areYouSureDelete')}
            </AlertDialogDescription>
          </AlertDialogHeader>
          <AlertDialogFooter className="flex-col sm:flex-row gap-2 sm:gap-0">
            <AlertDialogCancel 
              onClick={() => setDeleteDialogOpen(false)}
              className="w-full sm:w-auto min-h-[44px] touch-manipulation order-2 sm:order-1"
            >
              {t(locale, 'admin.common.cancel')}
            </AlertDialogCancel>
            <AlertDialogAction
              onClick={handleDeleteConfirm}
              className="bg-destructive text-destructive-foreground hover:bg-destructive/90 w-full sm:w-auto min-h-[44px] touch-manipulation order-1 sm:order-2"
            >
              {t(locale, 'admin.common.delete')}
            </AlertDialogAction>
          </AlertDialogFooter>
        </AlertDialogContent>
      </AlertDialog>
    </div>
  );
}
