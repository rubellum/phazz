import React from 'react';

import {Button, ChakraProvider, Container, Heading, Link, Stack, Text} from '@chakra-ui/react'

export default function (props) {
    return (
        <ChakraProvider>
            <Container my='1.5rem'>
                <Heading size='lg'><Link href='/'>Phazz</Link></Heading>
            </Container>

            <Container>
                <Heading size='sm' mt='1.5rem' color='gray'>Step.1</Heading>
                <Stack spacing={3} mt='1rem'>
                    <Text fontSize='sm'>
                        Register the information required for crawling.
                    </Text>
                    <Link href={props.path.register}>
                        <Button colorScheme='blue'>Register Site for Crawling</Button>
                    </Link>
                </Stack>

                <Heading size='sm' mt='1.5rem' color='gray'>Step.2</Heading>
                <Stack spacing={3} mt='1rem'>
                    <Text fontSize='sm'>
                        The next step is to actually perform the crawl.<br/>
                        The crawler saves data to storage.
                    </Text>
                    <Link href={props.path.job_search}>
                        <Button colorScheme='blue'>Run Crawl</Button>
                    </Link>
                </Stack>

                <Heading size='sm' mt='1.5rem' color='gray'>Step.3</Heading>
                <Stack spacing={3} mt='1rem'>
                    <Text fontSize='sm'>
                        Finally, check the data saved to storage by the crawler.
                    </Text>
                    <Link href={props.path.task_search}>
                        <Button colorScheme='blue'>Check Crawled Data</Button>
                    </Link>
                </Stack>
            </Container>
        </ChakraProvider>
    )
}
