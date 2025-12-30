import axios from 'axios';

const API_BASE_URL = 'http://localhost:8000/api';

// Create axios instance
const api = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add auth token to requests
api.interceptors.request.use((config) => {
  const token = localStorage.getItem('authToken');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

// Handle auth errors
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 401) {
      localStorage.removeItem('authToken');
      localStorage.removeItem('user');
      window.location.href = '/login';
    }
    return Promise.reject(error);
  }
);

// ============================================
// PUBLIC API (No Auth Required)
// ============================================

export const publicApi = {
  // Hero Slides
  getHeroSlides: () => api.get('/public/hero-slides'),
  
  // Achievements
  getAchievements: (limit = 6) => api.get(`/public/achievements?limit=${limit}`),
  
  // Programs
  getPrograms: () => api.get('/public/programs'),
  getProgram: (slug) => api.get(`/public/programs/${slug}`),
  
  // Partners
  getPartners: () => api.get('/public/partners'),
  
  // Testimonials
  getTestimonials: () => api.get('/public/testimonials'),
  
  // Articles
  getArticles: (limit = 10) => api.get(`/public/articles?limit=${limit}`),
  getArticle: (slug) => api.get(`/public/articles/${slug}`),
  
  // School Profiles
  getSchoolProfiles: () => api.get('/public/school-profiles'),
  getSchoolProfile: (key) => api.get(`/public/school-profiles/${key}`),
  
  // Teachers
  getTeachers: (params = {}) => api.get('/public/teachers', { params }),
  getTeacher: (id) => api.get(`/public/teachers/${id}`),
  getLeadership: () => api.get('/public/teachers/leadership'),
  
  // Schedules (Jadwal Pembelajaran)
  getSchedules: (params = {}) => api.get('/public/schedules', { params }),
  getClasses: () => api.get('/public/schedules/classes'),

  // Former Principals
  getFormerPrincipals: () => api.get('/public/former-principals'),

  // Galleries
  getGalleries: (type = null) => api.get('/public/galleries', { params: type ? { type } : {} }),
  getPhotos: () => api.get('/public/galleries/photos'),
  getReels: () => api.get('/public/galleries/reels'),
  getYoutubeVideos: () => api.get('/public/galleries/youtube'),
};

// ============================================
// ADMIN API (Auth Required)
// ============================================

// Helper to create FormData for file uploads
const createFormData = (data) => {
  const formData = new FormData();
  Object.keys(data).forEach(key => {
    if (data[key] !== null && data[key] !== undefined) {
      if (data[key] instanceof File) {
        formData.append(key, data[key]);
      } else if (typeof data[key] === 'boolean') {
        formData.append(key, data[key] ? '1' : '0');
      } else if (typeof data[key] === 'object') {
        formData.append(key, JSON.stringify(data[key]));
      } else {
        formData.append(key, data[key]);
      }
    }
  });
  return formData;
};

export const adminApi = {
  // ============ Achievements ============
  achievements: {
    list: (params = {}) => api.get('/admin/achievements', { params }),
    get: (id) => api.get(`/admin/achievements/${id}`),
    create: (data) => api.post('/admin/achievements', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/achievements/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/achievements/${id}`),
  },

  // ============ Articles ============
  articles: {
    list: (params = {}) => api.get('/admin/articles', { params }),
    get: (id) => api.get(`/admin/articles/${id}`),
    create: (data) => api.post('/admin/articles', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/articles/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/articles/${id}`),
  },

  // ============ Programs ============
  programs: {
    list: (params = {}) => api.get('/admin/programs', { params }),
    get: (id) => api.get(`/admin/programs/${id}`),
    create: (data) => api.post('/admin/programs', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/programs/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/programs/${id}`),
  },

  // ============ Partners ============
  partners: {
    list: (params = {}) => api.get('/admin/partners', { params }),
    get: (id) => api.get(`/admin/partners/${id}`),
    create: (data) => api.post('/admin/partners', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/partners/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/partners/${id}`),
  },

  // ============ Testimonials ============
  testimonials: {
    list: (params = {}) => api.get('/admin/testimonials', { params }),
    get: (id) => api.get(`/admin/testimonials/${id}`),
    create: (data) => api.post('/admin/testimonials', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/testimonials/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/testimonials/${id}`),
  },

  // ============ Hero Slides ============
  heroSlides: {
    list: (params = {}) => api.get('/admin/hero-slides', { params }),
    get: (id) => api.get(`/admin/hero-slides/${id}`),
    create: (data) => api.post('/admin/hero-slides', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/hero-slides/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/hero-slides/${id}`),
  },

  // ============ School Profiles ============
  schoolProfiles: {
    list: (params = {}) => api.get('/admin/school-profiles', { params }),
    get: (id) => api.get(`/admin/school-profiles/${id}`),
    create: (data) => api.post('/admin/school-profiles', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/school-profiles/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/school-profiles/${id}`),
  },

  // ============ Teachers ============
  teachers: {
    list: (params = {}) => api.get('/admin/teachers', { params }),
    get: (id) => api.get(`/admin/teachers/${id}`),
    create: (data) => api.post('/admin/teachers', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/teachers/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/teachers/${id}`),
  },

  // ============ Former Principals ============
  formerPrincipals: {
    list: (params = {}) => api.get('/admin/former-principals', { params }),
    get: (id) => api.get(`/admin/former-principals/${id}`),
    create: (data) => api.post('/admin/former-principals', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/former-principals/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/former-principals/${id}`),
  },

  // ============ Schedules ============
  schedules: {
    list: (params = {}) => api.get('/admin/schedules', { params }),
    get: (id) => api.get(`/admin/schedules/${id}`),
    create: (data) => api.post('/admin/schedules', data),
    update: (id, data) => api.put(`/admin/schedules/${id}`, data),
    delete: (id) => api.delete(`/admin/schedules/${id}`),
    bulkCreate: (schedules) => api.post('/admin/schedules/bulk', { schedules }),
  },

  // ============ Galleries ============
  galleries: {
    list: (params = {}) => api.get('/admin/galleries', { params }),
    get: (id) => api.get(`/admin/galleries/${id}`),
    create: (data) => api.post('/admin/galleries', createFormData(data), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    update: (id, data) => api.post(`/admin/galleries/${id}`, createFormData({ ...data, _method: 'PUT' }), {
      headers: { 'Content-Type': 'multipart/form-data' }
    }),
    delete: (id) => api.delete(`/admin/galleries/${id}`),
  },
};

export default api;
