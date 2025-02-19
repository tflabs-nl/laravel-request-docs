import { defineConfig } from 'astro/config'
import compress from 'astro-compress'
import react from '@astrojs/react'
import process from 'process'

import git from 'git-rev-sync'
const version = git.tag()
const versionParts = version.split('.')
const versionIncremented =
    versionParts[0] + '.' + (parseInt(versionParts[1]) + 1)
console.log('Version: ' + version + ' -> ' + versionIncremented)
process.env.PUBLIC_VERSION = versionIncremented

export default defineConfig({
    integrations: [react(), compress()],
    outDir: '../resources/dist',
    base: process.env.PUBLIC_BASE || '/documentation'
})
